<?php

namespace Esemve\Composerve\Cli\Commands;

use Esemve\Composerve\Cli\Terminal;

class Help extends Terminal
{
    public function __construct($arguments)
    {
        $this->println('Parameters:');
        $this->println('build'."\t"."-"."\tBuild packages.json");
    }
}