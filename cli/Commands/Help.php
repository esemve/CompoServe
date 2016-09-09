<?php

namespace Esemve\Composerve\Cli\Commands;

use Esemve\Composerve\Cli\Terminal;

class Help extends Terminal
{
    public function __construct($arguments)
    {
	$this->println("CompoServe");
	$this->println("----------");
        $this->println('Parameters:');
        $this->println('build'."\t\t\t"."-"."\tBuild packages.json from all repository");
        $this->println('build [package/name]'."\t"."-"."\tBuild only one repository");
    }
}