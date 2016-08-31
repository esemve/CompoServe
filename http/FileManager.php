<?php

namespace Esemve\Composerve\Http;

class FileManager
{
    protected $hide = ['.','..','.gitignore'];
    protected $jsonStorage;

    public function __construct()
    {
        $this->jsonStorage = rtrim(realpath(__DIR__.'/../storage/jsons/'),'/');
    }

    public function getArrayFromCachedJson($file)
    {
        return json_decode(file_get_contents($this->jsonStorage.'/'.$file.'.json'),true);
    }

    public function getCachedJsons()
    {
        $files = scandir($this->jsonStorage);
        $files = array_diff($files,$this->hide);

        return $files;
    }

}