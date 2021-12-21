<?php

    $path = __DIR__;

    while (!file_exists($path.'/autoload.php')) {
        $path .= '/..';
    }
    require_once $path.'/autoload.php';

    $app = new \Stui\StatuspageCLI\Console;
    $app->runWithTry($argv);
