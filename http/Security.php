<?php

namespace Esemve\Composerve\Http;

class Security
{
	protected $config;

	public function __construct()
	{
	    $configPath = __DIR__.'/../config/config.php';
	    if (file_exists($configPath))
	    {
		$this->config = require realpath($configPath);
	    }


	    $this->run();
	}


	protected function run()
	{

	    if (!empty($this->config['ipBlock']))
	    {

		if (empty($this->config['allowIp']))
		{
		    echo 'AllowIP is empty in config file!';
		    die();
		}
		
		$this->ipBlock($this->config['allowIp']);
	    }
	}


	protected function ipBlock($ips)
	{
	    if (!in_array($_SERVER['REMOTE_ADDR'],$ips))
	    {
		header('HTTP/1.0 403 Forbidden');
		die('HTTP/1.0 403 Forbidden');
	    }
	}


}
