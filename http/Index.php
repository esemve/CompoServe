<?php

namespace Esemve\Composerve\Http;

class Index
{
    public function __construct()
    {
	$packages = new Packages();
	$packageList = $packages->buildPackageJson();

	$packages = json_decode($packageList,true);
	if (empty($packages))
	{
	    $packages = [];
	}

	
	require realpath(__DIR__.'/templates/index.php');
    }
}