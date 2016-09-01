<?php

namespace Esemve\Composerve\Http;

class Packages
{

    protected $repositories;

    public function __construct()
    {
        $this->fileManager = new FileManager();
        $this->repositories = require realpath(__DIR__.'/../config/repositories.php');

        $json = $this->buildPackageJson();
    }

    protected function buildPackageJson()
    {
        $packages = [];

        foreach ($this->repositories as $package => $path)
        {
          $path = $this->fileManager->getArrayFromCachedJson(md5($package));
          $packages[$package] = $path;
        }
		
		$output = [];
		$output['packages'] = $packages;

        $url = 'http:\/\/'.$_SERVER['HTTP_HOST'];

        $json = json_encode($output);
        echo str_replace('#SITEURL#',$url,$json);

    }

}