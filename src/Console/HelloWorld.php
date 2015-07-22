<?php

namespace Console;

use Edefine\Framework\Console\AbstractJob;
use Edefine\Framework\Console\Input\InputInterface;
use Edefine\Framework\Console\Output\OutputInterface;

class HelloWorld extends AbstractJob
{
    public function getName()
    {
        return 'hello:world';
    }

    public function getInfo()
    {
        return 'Shows "Hello, World!" message';
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello, World!');
    }
}