<?php

namespace View\Extension;

use Edefine\Framework\Session\Session;
use Repository\UserRepository;

class UserExtension extends \Twig_Extension
{
    private $session;
    private $userRepository;

    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function getName()
    {
        return 'user';
    }

    public function getGlobals()
    {
        $userId = $this->session->get('userId');

        if ($userId) {
            $user = $this->userRepository->findOneById($userId);
        } else {
            $user = null;
        }

        return [
            'user' => $user
        ];
    }
}