<?php

namespace Esemve\Composerve\Cli\Commands;

use Esemve\Composerve\Cli\Terminal;

class Build extends Terminal
{
    protected $repositories = [];
	protected $infos;

    public function __construct($parameters)
    {
        $this->repositories = require realpath(__DIR__.'/../../config/repositories.php');
		
        if (empty($parameters))
        {
            $this->parseRepositories();
        }
	else
	{
	    $this->parseOneRepository($parameters[0]);
	}
    }

    public function parseRepositories()
    {
        if (!empty($this->repositories)) {
            foreach ($this->repositories as $package => $parameters) {
                $this->parseRepository($package);
            }
        }
    }

    public function parseOneRepository($packageName)
    {
	if (!$this->foundInRepositoryarray($packageName))
	{
	    echo $packageName.' package not found!';
	}

	$this->parseRepository($packageName);
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

	$info = $this->getRepositoryInfos($package);
    
	if (!empty($info['useGitPull']))
	{
	    $this->useGitPull($path);
	}

        $tags = $this->parseGitTags($package,$path);
		
        if (empty($tags))
        {
            $this->error('No tags in '.$package.'!');
            return;
        }

        $this->generateCacheJsonFile($package,$tags,__DIR__.'/../../storage/jsons/'.md5($package).'.json');
		$this->generateZipFiles($package,$tags);
    }

    protected function getRepositoryInfos($package)
    {
	return $this->repositories[$package];
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

	if (empty($this->repositories[$package]['path']))
	{
	    if (!empty($this->repositories[$package]['remote']))
	    {
		$this->repositories[$package]['path'] = $this->getRemoteRepositoryPath($package);
		$this->repositories[$package]['useGitPull'] = true;
	    }
	}

        $path = rtrim($this->repositories[$package]['path'],'/');

        if (!file_exists($path.'/.git'))
        {
            $this->error($path.' is not a valid git repository!');
            return;
        }

        return $path;
    }

    protected function useGitPull($path)
    {
	chdir($path);
	$this->exec('git pull origin master --tags');
    }

    protected function parseGitTags($package,$path)
    {
        $output = [];
	chdir($path);
	$packageHash = md5($package);

        $tagrows = $this->exec('git show-ref --tags');
        foreach ($tagrows AS $row)
        {
            $row = explode(' refs/tags/',$row);
            if (count($row)==2)
            {
                $commit = $row[0];
                $tag = $row[1];

                $this->println('Founded ('.$package.'): '.$commit. " - ".$tag);
                $output[$tag] = $commit;
		$this->infos[$packageHash][$commit] = $this->getComposerData($commit);
				
            }
        }
        return $output;
    }

    public function generateCacheJsonFile($package,$tags,$file)
    {
        $save = [];	
		
        foreach ($tags AS $tag => $commit)
        {
			preg_match('/[0-9vab.]+/',$tag,$o);
			if ((!empty($o)) && ($o[0]==$tag))
			{
				$save[$tag] = [
					'name' => $package,
					'version' => $tag,
					'dist' => [
						'url' => '#SITEURL#/download/'.md5($package)."/".$commit.".zip",
						'type' => 'zip'
					]
				];
				
				if (!empty($this->infos[md5($package)][$commit]))
				{
					$myComposerDatas = $this->infos[md5($package)][$commit];
					if (!empty($myComposerDatas['minimum-stability']))
					{
						$save[$tag]['minimum-stability'] = $myComposerDatas['minimum-stability'];
					}
					if (!empty($myComposerDatas['autoload']))
					{
						$save[$tag]['autoload'] = $myComposerDatas['autoload'];
					}
					if (!empty($myComposerDatas['require']))
					{
						$save[$tag]['require'] = $myComposerDatas['require'];
					}
					if (!empty($myComposerDatas['require-dev']))
					{
						$save[$tag]['require-dev'] = $myComposerDatas['require-dev'];
					}				

				}
			}
			
			
        }

        $json = json_encode($save);
        file_put_contents($file,$json);
    }
	
	public function generateZipFiles($package,$tags)
	{
		$folder = __DIR__.'/../../storage/zips/'.md5($package);
		
		if (!file_exists($folder))
		{
			mkdir($folder);
		}
		foreach ($tags AS $tag => $commit)
		{
			$file = $folder.'/'.$commit.'.zip';
			if (!file_exists($file))
			{
				$this->println('Generate '.$package.' - '.$tag.' zipball');
				$this->exec('git archive --format zip --output '.$file.' '.$commit);
			}
		}
	}
	
	public function getComposerData($commit)
	{
		$json = $this->exec('git show '.$commit.':composer.json');
		$json = implode($json);
		
		$datas = json_decode($json,true);
		if (empty($datas))
		{
			return null;
		}
		return $datas;
	
	}

	protected function getRemoteRepositoryPath($package)
	{
	    if (!empty($this->repositories[$package]['remote']))
	    {
		$remote = $this->repositories[$package]['remote'];

		$localFolder = __DIR__.'/../../storage/repos/'.md5($remote);
		if (file_exists($localFolder))
		{
		    return $localFolder;
		}
		else
		{
		    mkdir($localFolder);
		    if (!file_exists($localFolder))
		    {
			die($localFolder.' not found!');
		    }

		    chdir($localFolder);
		    $this->exec('git init .');
		    $this->exec('git remote add origin '.$remote);
		    $this->exec('git pull origin master --tags');
		    return $localFolder;
		}
	    }
	}
}