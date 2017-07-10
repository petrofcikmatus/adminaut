<?php

namespace Adminaut\Authentication\Adapter;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserFailedLoginEntity;
use Adminaut\Repository\UserFailedLoginRepository;
use Adminaut\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Class AuthAdapter
 * @package Adminaut\Authentication\Adapter
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var int
     */
    private $failedLoginCount = 3;

    /**
     * @var int
     */
    private $failedLoginTimeout = 30; // 30 seconds

    /**
     * @var string
     */
    private $userEmail;

    /**
     * @var string
     */
    private $userPassword;

    //-------------------------------------------------------------------------

    /**
     * AuthAdapter constructor.
     * @param EntityManager $entityManager
     * @param array $options
     */
    public function __construct(EntityManager $entityManager, array $options = [])
    {
        $this->entityManager = $entityManager;
    }

    //-------------------------------------------------------------------------

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = (string)$userEmail;
    }

    /**
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @param string $userPassword
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = (string)$userPassword;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        if (null === $this->userEmail || null === $this->userPassword) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, [_('Missing credentials.')]);
        }


        /** @var UserEntity $user */
        $user = $this->getUserRepository()->findOneBy([
            'email' => $this->userEmail,
            'deleted' => false,
            'active' => true,
        ]);

        if (null === $user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, [_('Account does not exist.')]);
        }


        $since = new \DateTime('-' . $this->failedLoginTimeout . ' seconds');

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->gte('inserted', $since));
        $criteria->andWhere(Criteria::expr()->eq('userId', $user->getId()));
        $criteria->orderBy(['id' => 'asc']);

        /** @var ArrayCollection $failedLogins */
        $failedLogins = $this->getUserFailedLoginRepository()->matching($criteria);
        $failedLoginsCount = $failedLogins->count();

        if (0 !== $failedLoginsCount) {

            /** @var UserFailedLoginEntity $lastFailedLogin */
            $lastFailedLogin = $failedLogins->last();

            $timeToWait = $lastFailedLogin->getInserted()->diff($since);

            if ($this->failedLoginCount <= $failedLoginsCount && $this->failedLoginTimeout >= $timeToWait->s) {
                return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [printf(_('You have to wait for %s seconds.'), $timeToWait->s)]);
            }
        }

        if (true !== PasswordHelper::verify($this->userPassword, $user->getPassword())) {

            $failedLogin = new UserFailedLoginEntity($user);

            $this->entityManager->persist($failedLogin);
            $this->entityManager->flush();

            if ($this->failedLoginCount <= $this->getUserFailedLoginRepository()->matching($criteria)->count()) {
                return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [printf(_('Invalid credentials. And you have to wait for %s seconds.'), $this->failedLoginTimeout)]);
            }

            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [_('Invalid credentials.')]);
        }

        return new Result(Result::SUCCESS, $this->userEmail, [_('Authenticated successfully.')]);
    }

    //-------------------------------------------------------------------------

    /**
     * @return EntityRepository|UserRepository
     */
    private function getUserRepository()
    {
        return $this->entityManager->getRepository(UserEntity::class);
    }

    /**
     * @return EntityRepository|UserFailedLoginRepository
     */
    private function getUserFailedLoginRepository()
    {
        return $this->entityManager->getRepository(UserFailedLoginEntity::class);
    }
}