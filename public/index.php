<?php

require realpath(__DIR__.'/../vendor/autoload.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_URI']=='/packages.json')
{
    new Esemve\Composerve\Http\Packages();
    die();
}

new Esemve\Composerve\Http\Index();
