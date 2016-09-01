<?php

namespace Esemve\Composerve\Cli;

use Esemve\Composerve\Cli\Commands\Build;
use Esemve\Composerve\Cli\Commands\Help;

class Commander
{

    public function __construct($arguments)
    {
        if (empty($arguments[1]))
        {
            $arguments[1] = 'help';
        }

        $pass = array_slice($arguments,2);

        switch (strtolower($arguments[1]))
        {
            case 'build':
                return new Build($pass);

            default:
                return new Help($pass);
        }
    }
}