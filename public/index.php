<?php

require realpath(__DIR__.'/../vendor/autoload.php');

if ($_SERVER['REQUEST_URI']=='/packages.json')
{
    new Esemve\Composerve\Http\Packages();
    die();
}

new Esemve\Composerve\Http\Index();
