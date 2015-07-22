<?php

namespace Controller;

use Form\LoginForm;
use Form\ValueObject\LoginObject;

class SecurityController extends AbstractController
{
    public function loginAction()
    {
        $loginForm = new LoginForm('login');
        $loginObject = new LoginObject();

        $loginForm->bindData($loginObject);

        if ($this->getParam('login')) {
            $loginForm->bindRequest($this->getRequest());

            $user = $this->getContainer()->get('loginHandler')->login($loginObject);

            if ($user) {
                return $this->redirect($this->getPath('home', 'index'));
            }
        }

        return $this->renderView([
            'loginForm' => $loginForm
        ]);
    }

    public function logoutAction()
    {
        $this->getContainer()->get('loginHandler')->logout();

        return $this->redirect($this->getPath('home', 'index'));
    }
}