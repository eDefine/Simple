<?php

use Edefine\Framework\Cache\Memcached;
use Edefine\Framework\Config;
use Edefine\Framework\Cookie\Cookie;
use Edefine\Framework\Database;
use Edefine\Framework\Event\Dispatcher;
use Edefine\Framework\Http;
use Edefine\Framework\Log\Writer;
use Edefine\Framework\Mail\Mailer;
use Edefine\Framework\ORM\EntityManager;
use Edefine\Framework\Pdf\Generator;
use Edefine\Framework\Routing\Router;
use Edefine\Framework\Session;
use Edefine\Framework\Utils\System;
use Edefine\Framework\View\Extension as TwigExtension;
use Edefine\Framework\View\Twig;

class ServiceFactory
{
    private static $instance;
    private $services = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}

    /**
     * @return Config\Config
     */
    public function getConfig()
    {
        if (!isset($this->services['config'])) {
            $reader = new Config\Reader();
            $this->services['config'] = $reader->read(sprintf('%s/config.ini', APP_DIR));
        }

        return $this->services['config'];
    }

    /**
     * @return Cookie
     */
    public function getCookie()
    {
        if (!isset($this->services['cookie'])) {
            $this->services['cookie'] = new Cookie();
        }

        return $this->services['cookie'];
    }

    /**
     * @return Database\Connection
     */
    public function getDatabase()
    {
        if (!isset($this->services['database'])) {
            $config = $this->getConfig();
            if ($config->get('application.environment') == 'development') {
                $databaseLogger = new Writer(sprintf('%s/log/database.log', APP_DIR));
                $connection = new Database\LoggedConnection($config, $databaseLogger);
            } else {
                $connection = new Database\Connection($config);
            }
            $this->services['database'] = $connection;
        }

        return $this->services['database'];
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        if (!isset($this->services['dispatcher'])) {
            $dispatcher = new Dispatcher();

            // User defined listeners
            $dispatcher
                ->addListener(new Event\RememberMeListener($this->getLoginHandler()))
                ->addListener(new Event\LastActivityListener($this->getEntityManager(), $this->getUserRepository()));

            $this->services['dispatcher'] = $dispatcher;
        }

        return $this->services['dispatcher'];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!isset($this->services['entityManager'])) {
            $this->services['entityManager'] = new EntityManager($this->getDatabase());
        }

        return $this->services['entityManager'];
    }

    /**
     * @return Session\FlashBag
     */
    public function getFlashBag()
    {
        if (!isset($this->services['flashBag'])) {
            $this->services['flashBag'] = new Session\FlashBag($this->getSession());
        }

        return $this->services['flashBag'];
    }

    /**
     * @return Writer
     */
    public function getLogger()
    {
        if (!isset($this->services['logger'])) {
            $this->services['logger'] = new Writer(sprintf('%s/log/dev.log', APP_DIR));
        }

        return $this->services['logger'];
    }

    /**
     * @return Mailer
     */
    public function getMailer()
    {
        if (!isset($this->services['mailer'])) {
            $this->services['mailer'] = new Mailer($this->getConfig());
        }

        return $this->services['mailer'];
    }

    /**
     * @return Memcached
     */
    public function getMemcached()
    {
        if (!isset($this->services['memcached'])) {
            $this->services['memcached'] = new Memcached($this->getConfig());
        }

        return $this->services['memcached'];
    }

    /**
     * @return Generator
     */
    public function getPdfGenerator()
    {
        if (!isset($this->services['pdfGenerator'])) {
            $this->services['pdfGenerator'] = new Generator($this->getConfig());
        }

        return $this->services['pdfGenerator'];
    }

    /**
     * @return Http\Request
     */
    public function getRequest()
    {
        if (!isset($this->services['request'])) {
            $this->services['request'] = new Http\Request($this->getSession(), $this->getCookie());
        }

        return $this->services['request'];
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!isset($this->services['router'])) {
            $this->services['router'] = new Router($this->getConfig(), $this->getServer());
        }

        return $this->services['router'];
    }

    /**
     * @return Http\Server
     */
    public function getServer()
    {
        if (!isset($this->services['server'])) {
            $this->services['server'] = new Http\Server();
        }

        return $this->services['server'];
    }

    /**
     * @return Session\Session
     */
    public function getSession()
    {
        if (!isset($this->services['session'])) {
            $this->services['session'] = new Session\Session();
        }

        return $this->services['session'];
    }

    /**
     * @return System
     */
    public function getSystem()
    {
        if (!isset($this->services['system'])) {
            $this->services['system'] = new System();
        }

        return $this->services['system'];
    }

    /**
     * @return Twig
     */
    public function getTwig()
    {
        if (!isset($this->services['twig'])) {
            $twig = new Twig(sprintf('%s/src/View', APP_DIR));

            $twig->addExtension(new TwigExtension\FlashExtension($this->getFlashBag()));
            $twig->addExtension(new TwigExtension\FormExtension());
            $twig->addExtension(new TwigExtension\RouterExtension($this->getRouter(), $this->getRequest()));

            // User defined extensions
            $twig->addExtension(new View\Extension\UserExtension($this->getSession(), $this->getUserRepository()));

            $this->services['twig'] = $twig;
        }

        return $this->services['twig'];
    }

    /*
     * Repositories
     */

    /**
     * @return Repository\UserRepository
     */
    public function getUserRepository()
    {
        if (!isset($this->services['userRepository'])) {
            $this->services['userRepository'] = new Repository\UserRepository('Entity\User', $this->getDatabase());
        }

        return $this->services['userRepository'];
    }

    /*
     * User defined services
     */

    /**
     * @return Service\LoginHandler
     */
    public function getLoginHandler()
    {
        if (!isset($this->services['loginHandler'])) {
            $this->services['loginHandler'] = new Service\LoginHandler(
                $this->getEntityManager(),
                $this->getUserRepository(),
                $this->getSession(),
                $this->getCookie(),
                $this->getFlashBag(),
                $this->getLogger()
            );
        }

        return $this->services['loginHandler'];
    }
}