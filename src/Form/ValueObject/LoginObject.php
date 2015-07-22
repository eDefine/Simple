<?php

namespace Form\ValueObject;

class LoginObject
{
    private $email;

    private $plainPassword;

    private $rememberMe;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;

        return $this;
    }

    public function getRememberMe()
    {
        return $this->rememberMe;
    }
} 