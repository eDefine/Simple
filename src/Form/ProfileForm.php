<?php

namespace Form;

use Edefine\Framework\Form\AbstractForm;
use Edefine\Framework\Form\Input\File;
use Edefine\Framework\Form\Input\Text;

class ProfileForm extends AbstractForm
{
    protected function build()
    {
        $this
            ->addInput(new Text('firstName'))
            ->addInput(new Text('lastName'))
            ->addInput(new Text('email'))
            ->addInput(new File('picture'));
    }
}