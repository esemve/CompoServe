<?php

require realpath(__DIR__.'/../vendor/autoload.php');

new Esemve\Composerve\Http\Security();

if ($_SERVER['REQUEST_URI']=='/packages.json')
{
    $packages = new Esemve\Composerve\Http\Packages();
    echo $packages->buildPackageJson();

    die();
}

$uris = explode('/',$_SERVER['REQUEST_URI']);
if (!empty($uris[1]))
{
    if ($uris[1]=='download')
    {
	new Esemve\Composerve\Http\Download($uris);
    }
}



new Esemve\Composerve\Http\Index();
