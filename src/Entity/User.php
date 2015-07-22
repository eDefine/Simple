<?php

namespace Entity;

use Edefine\Framework\Entity\AbstractEntity;
use Edefine\Framework\Mail\RecipientInterface;
use Edefine\Framework\Storage\UploadedFile;

class User extends AbstractEntity implements RecipientInterface
{
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $salt;
    private $session;
    private $roles = [];
    private $created;
    private $lastActivity;
    private $picture;
    private $pictureName;

    public function __construct()
    {
        $this->salt = md5(time() . rand());
        $this->created = new \DateTime();
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getName()
    {
        return $this->getFullName();
    }

    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPlainPassword($plainPassword)
    {
        return $this->setPassword(md5($plainPassword . '_' . $this->salt));
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setLastActivity(\DateTime $lastActivity = null)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    public function setPicture(UploadedFile $picture)
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    public function getPictureName()
    {
        return $this->pictureName;
    }

    public function getPicturePath()
    {
        if ($this->getPictureName()) {
            return sprintf('/uploads/%d/%s', $this->getId(), $this->getPictureName());
        } else {
            return '/img/no-picture.png';
        }
    }

    public function getTableName()
    {
        return 'user';
    }

    public function getMappedFields()
    {
        return [
            'id',
            'firstName',
            'lastName',
            'email',
            'password',
            'salt',
            'session',
            'roles',
            'created',
            'lastActivity',
            'pictureName'
        ];
    }

    public function getDateTimeFields()
    {
        return [
            'created',
            'lastActivity'
        ];
    }

    public function getArrayFields()
    {
        return [
            'roles'
        ];
    }
}