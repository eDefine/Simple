<?php

namespace Controller;

use Edefine\Framework\Controller\AbstractController as BaseAbstractController;

class AbstractController extends BaseAbstractController
{
    protected function getUser()
    {
        $userId = $this->getSession()->get('userId');

        if (!$userId) {
            return null;
        }

        $user = $this->getContainer()->get('userRepository')->findOneById($userId);

        return $user;
    }
}