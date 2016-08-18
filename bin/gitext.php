<?php
require_once 'autoload-init.php';


$root = realpath(__DIR__.'/..');
use \Rikby\GitExt\Console;
$app = new Console\Application();
$app->add(new Console\Command\Install($root));
$app->run();
