<?php
require_once 'autoload-init.php';

use \Rikby\GitExt\Console;

$root = realpath(__DIR__.'/..');
$app = new Console\Application();
$app->add(new Console\Command\Install($root));
$app->add(new Console\Command\Source($root));
$app->run();
