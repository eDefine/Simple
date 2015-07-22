<?php

namespace Form;

use Edefine\Framework\Form\AbstractForm;
use Edefine\Framework\Form\Input\Checkbox;
use Edefine\Framework\Form\Input\Password;
use Edefine\Framework\Form\Input\Text;

class LoginForm extends AbstractForm
{
    protected function build()
    {
        $this
            ->addInput(new Text('email'))
            ->addInput(new Password('plainPassword'))
            ->addInput(new Checkbox('rememberMe'));
    }
}