<?php

namespace Esemve\Composerve\Cli\Commands;

use Esemve\Composerve\Cli\Terminal;

class Build extends Terminal
{
    protected $repositories = [];

    public function __construct($parameters)
    {
        $this->repositories = require realpath(__DIR__.'/../../config/repositories.php');

        if (empty($parameters))
        {
            $this->parseRepositories();
        }
    }

    public function parseRepositories()
    {
        if (!empty($this->repositories)) {
            foreach ($this->repositories as $package => $path) {
                $this->parseRepository($package);
            }
        }
    }

    public function parseRepository($package)
    {

        if (!$this->foundInRepositoryArray($package))
        {
            return;
        }

        $path = $this->getRepositoryPath($package);
        if (empty($path))
        {
            return;
        }


        $tags = $this->parseGitTags($package,$path);
        if (empty($tags))
        {
            $this->error('No tags in '.$package.'!');
            return;
        }

        $this->generateCacheJsonFile($package,$tags,__DIR__.'/../../storage/jsons/'.md5($package).'.json');
    }

    protected function foundInRepositoryArray($package)
    {
        if (empty($this->repositories[$package])) {
            $this->error($package . ' not found!');
            return false;
        }

        return true;
    }

    protected function getRepositoryPath($package)
    {
        $path = rtrim($this->repositories[$package],'/');

        if (!file_exists($path.'/.git'))
        {
            $this->error($path.' is not a valid git repository!');
            return;
        }

        return $path;
    }

    protected function parseGitTags($package,$path)
    {
        $output = [];
        $tagrows = $this->exec('cd '.$path.'; git show-ref --tags');
        foreach ($tagrows AS $row)
        {
            $row = explode(' refs/tags/',$row);
            if (count($row)==2)
            {
                $commit = $row[0];
                $tag = $row[1];

                $this->println('Founded ('.$package.'): '.$commit. " - ".$tag);
                $output[$tag] = $commit;
            }
        }
        return $output;
    }

    public function generateCacheJsonFile($package,$tags,$file)
    {
        $save = [];

        foreach ($tags AS $tag => $commit)
        {
            $save[$tag] = [
                'name' => $package,
                'version' => $tag,
                'dist' => [
                    'url' => '#SITEURL#/download/'.urlencode($package)."/".$commit.".zip",
                    'type' => 'zip'
                ]
            ];
        }

        $json = json_encode($save);
        file_put_contents($file,$json);
    }
}