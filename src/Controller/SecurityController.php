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

            $user = $this->getServiceFactory()->getLoginHandler()->login($loginObject);

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
        $this->getServiceFactory()->getLoginHandler()->logout();

        return $this->redirect($this->getPath('home', 'index'));
    }
}