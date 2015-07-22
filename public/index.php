<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('APP_DIR', dirname(__DIR__));

require_once(__DIR__ . '/../vendor/autoload.php');

$container = \DependencyInjection::initContainer();
$bootstrap = new Edefine\Framework\Bootstrap($container);
$bootstrap->run();