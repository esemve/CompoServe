<?php

require realpath(__DIR__.'/../vendor/autoload.php');

new Esemve\Composerve\Http\Security();

if ($_SERVER['REQUEST_URI']=='/packages.json')
{
    $packages = new Esemve\Composerve\Http\Packages();
    echo $packages->buildPackageJson();

    die();
}

new Esemve\Composerve\Http\Index();
