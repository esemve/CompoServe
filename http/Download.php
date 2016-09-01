<?php

namespace Esemve\Composerve\Http;

class Download
{
	public function __construct($uris)
	{
		$remove = ['.','..','/','\\'];
		$uris[3] = str_replace('.zip','',$uris[3].'.zip');
		$file = str_replace('.zip','',__DIR__.'/../storage/zips/'.str_replace($remove,'',$uris[2]).'/'.str_replace($remove,'',$uris[3])).'.zip';
		
		if (file_exists($file))
		{
				$fp = fopen($file, 'rb');

				header("Content-Type: application/zip");
				@header("Content-Disposition: attachment; filename=package.zip");

				fpassthru($fp);			
				die();
		}
	}
}
