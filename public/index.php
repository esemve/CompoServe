<?php

require realpath(__DIR__.'/../vendor/autoload.php');

ini_set('display_errors',1);
error_reporting(E_ALL);


$uris = explode('/',$_SERVER['REQUEST_URI']);

if (!empty($uris[1]))
{
	if ($uris[1]=='packages.json')
	{
		new Esemve\Composerve\Http\Packages();
		die();
	}
	else if (($uris[1]=='download') && (count($uris)==4))
	{
		new Esemve\Composerve\Http\Download($uris);
		die();	
	}


}
new Esemve\Composerve\Http\Index();
