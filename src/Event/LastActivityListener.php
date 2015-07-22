<?php

namespace Event;

use Edefine\Framework\Event\Listener;
use Edefine\Framework\Event\RequestEvent;
use Edefine\Framework\ORM\EntityManager;
use Repository\UserRepository;

class LastActivityListener extends Listener
{
    private $manager;
    private $userRepository;

    public function __construct(EntityManager $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    public function dispatch($event)
    {
        /** @var RequestEvent $event */
        $request = $event->getRequest();
        $session = $request->getSession();
        $userId = $session->get('userId');

        if ($userId) {
            $user = $this->userRepository->findOneById($userId);
            $user->setLastActivity(new \DateTime());
            $this->manager->save($user);
        }
    }

    public function getEventName()
    {
        return 'kernel.request';
    }
}