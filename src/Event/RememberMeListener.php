<?php

namespace Event;

use Edefine\Framework\Event\Listener;
use Edefine\Framework\Event\RequestEvent;
use Service\LoginHandler;

class RememberMeListener extends Listener
{
    private $loginHandler;

    public function __construct(LoginHandler $loginHandler)
    {
        $this->loginHandler = $loginHandler;
    }

    public function dispatch($event)
    {
        /** @var RequestEvent $event */
        $request = $event->getRequest();
        $session = $request->getSession();
        $cookie = $request->getCookie();

        $sessionId = $cookie->get('session');

        if (!$session->get('userId') && $sessionId) {
            $this->loginHandler->loginBySessionId($sessionId);
        }
    }

    public function getEventName()
    {
        return 'kernel.request';
    }
}