<?php

namespace SONUser\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Math\Rand;
use Zend\Crypt\Key\Derivation\Pbkdf2;
use Zend\Stdlib\Hydrator;

/**
 * SonuserUsers
 *
 * @ORM\Table(name="sonuser_users")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="SONUser\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=true)
     */
    private $activationKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct(array $options = array())
    {
        /*$hydrator = new Hydrator\ClassMethods;
        $hydrator->hydrate($options, $this);*/

        (new Hydrator\ClassMethods)->hydrate($options, $this);

        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");

        $this->salt = base64_encode(Rand::getBytes(8, true));
        $this->activationKey = md5($this->email . $this->salt);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        return $this->nome = $nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        return $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        return $this->password = $this->encryptPassword($password);
    }

    public function encryptPassword($password)
    {
        return base64_encode(Pbkdf2::calc("sha256", $password, $this->salt, 10000, strlen($password*2)));
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        return $this->salt = $salt;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        return $this->active = $active;
    }

    public function getActivationKey()
    {
        return $this->activationKey;
    }

    public function setActivationKey($activationKey)
    {
        return $this->activationKey = $activationKey;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUpdatedAt()
    {
        return $this->updatedAt = new \DateTime("now");
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt()
    {
        return $this->createdAt = new \DateTime("now");
    }

}

