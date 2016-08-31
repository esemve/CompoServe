<?php

namespace Esemve\Composerve\Cli;

class Terminal
{
    public function println($text)
    {
        echo $text."\r\n";
    }

    public function error($text)
    {
        echo "[ERROR] ".$text."\r\n";

    }

    public function exec($command)
    {
        exec($command,$output);
        return $output;
    }
}