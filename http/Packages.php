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
        //var_dump($this->repositories);
    }

    protected function buildPackageJson()
    {
        $packages = [];

        foreach ($this->repositories as $package => $path)
        {
          $path = $this->fileManager->getArrayFromCachedJson(md5($package));
          $packages[$package] = $path;
        }

        $packages['packages'] = $packages;

        $url = (empty($_SERVER['HTTPS'])?'http:\/\/':'https:\/\/').$_SERVER['HTTP_HOST'];

        $json = json_encode($packages);
        echo str_replace('#SITEURL#',$url,$json);

    }

}