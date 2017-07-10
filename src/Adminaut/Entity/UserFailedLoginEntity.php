<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserFailedLoginEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserFailedLoginRepository")
 * @ORM\Table(name="adminaut_user_failed_login")
 * @ORM\HasLifecycleCallbacks()
 */
class UserFailedLoginEntity extends Base
{
    /**
     * @ORM\Column(type="integer", name="user_id")
     * @var int
     */
    protected $userId;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    //-------------------------------------------------------------------------

    /**
     * Owning side.
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="failedLogins")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var UserEntity
     */
    protected $user;

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    //-------------------------------------------------------------------------

    /**
     * @ORM\Column(type="string", name="access_token_hash", unique=true)
     * @var string
     */
    protected $accessTokenHash;

    /**
     * @return string
     */
    public function getAccessTokenHash()
    {
        return $this->accessTokenHash;
    }

    //-------------------------------------------------------------------------

    /**
     * UserFailedLoginEntity constructor.
     * @param UserEntity $user
     * @param string $accessTokenHash
     */
    public function __construct(UserEntity $user, $accessTokenHash)
    {
        $this->user = $user;
        $this->accessTokenHash = (string)$accessTokenHash;
    }
}
