<?php

    require_once '../vendor/autoload.php';

    $app = new \Stui\StatuspageCLI\Console;
    $app->runWithTry($argv); // $argv is a global variable containing command line arguments.
