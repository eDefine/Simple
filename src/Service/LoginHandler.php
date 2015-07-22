<?php

namespace Service;

use Edefine\Framework\Cookie\Cookie;
use Edefine\Framework\Log\Writer;
use Edefine\Framework\ORM\EntityManager;
use Edefine\Framework\Session\FlashBag;
use Edefine\Framework\Session\Session;
use Form\ValueObject\LoginObject;
use Repository\UserRepository;

class LoginHandler
{
    private $manager;
    private $userRepository;
    private $session;
    private $cookie;
    private $flashBag;
    private $logger;

    public function __construct(EntityManager $manager, UserRepository $userRepository, Session $session, Cookie $cookie, FlashBag $flashBag, Writer $logger)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->cookie = $cookie;
        $this->flashBag = $flashBag;
        $this->logger = $logger;
    }

    public function login(LoginObject $loginObject)
    {
        $user = null;

        try {
            $user = $this->userRepository->loginUser($loginObject);

            $message = sprintf('User %s is now logged in', $user);
            $this->flashBag->add('success', $message);
            $this->logger->logInfo($message);

            if (!$user->getSession()) {
                $user->setSession(md5(rand()));
                $this->manager->save($user);
            }

            if ($loginObject->getRememberMe()) {
                $this->cookie->set('session', $user->getSession());
            }

            $this->session->set('userId', $user->getId());
        } catch (\Exception $ex) {
            $this->flashBag->add('error', $ex->getMessage());
            $this->logger->logError($ex->getMessage());
        }

        return $user;
    }

    public function loginBySessionId($sessionId)
    {
        try {
            $user = $this->userRepository->loginUserBySessionId($sessionId);

            $message = sprintf('User %s logged in from cookie', $user);
            $this->flashBag->add('success', $message);
            $this->logger->logInfo($message);
            $this->session->set('userId', $user->getId());
        } catch (\RuntimeException $ex) {
            $user = null;
            $this->cookie->set('session', null);
            $this->session->set('userId', null);
        }

        return $user;
    }

    public function logout()
    {
        $userId = $this->session->get('userId');
        $user = $this->userRepository->findOneById($userId);
        $user->setSession(null);
        $this->manager->save($user);

        $this->cookie->set('session', null);
        $this->session->set('userId', null);

        $message = 'User successfully logged out';
        $this->flashBag->add('success', $message);
        $this->logger->logInfo($message);
    }
}