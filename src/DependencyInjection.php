<?php

use Edefine\Framework\Dependency\Container;
use Edefine\Framework\Dependency\Injection;

class DependencyInjection extends Injection
{
    public static function initContainer()
    {
        $container = parent::initContainer();

        self::initRepositories($container);

        $container->add('loginHandler', new Service\LoginHandler(
            $container->get('manager'),
            $container->get('userRepository'),
            $container->get('session'),
            $container->get('cookie'),
            $container->get('flashBag'),
            $container->get('logger')
        ));

        self::initTwigExtensions($container);
        self::initEventDispatcher($container);

        return $container;
    }

    private static function initTwigExtensions(Container $container)
    {
        $twig = $container->get('twig');

        $twig->addExtension(new View\Extension\UserExtension(
            $container->get('session'),
            $container->get('userRepository')
        ));
    }

    private static function initRepositories(Container $container)
    {
        $container->add('userRepository', new Repository\UserRepository('Entity\User', $container->get('database')));
    }

    private static function initEventDispatcher(Container $container)
    {
        $dispatcher = $container->get('dispatcher');

        $dispatcher
            ->addListener(new Event\RememberMeListener($container->get('loginHandler')))
            ->addListener(new Event\LastActivityListener($container->get('manager'), $container->get('userRepository')));
    }
}