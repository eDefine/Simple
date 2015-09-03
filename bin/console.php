<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('APP_DIR', dirname(__DIR__));

require_once(__DIR__ . '/../vendor/autoload.php');

$application = new Edefine\Framework\Console\Application();

$application->addJob(new Edefine\Framework\Console\FixturesLoad());

$application->addJob(new Console\HelloWorld());

$application->run();