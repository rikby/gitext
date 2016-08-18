<?php
$internal = __DIR__ . '/../vendor';
$external = __DIR__ . '/../../../../vendor';

if (realpath($internal)) {
    $dir = realpath($internal);
} elseif (realpath($external)) {
    // package required in another composer.json
    $dir = realpath($external);
} else {
    die('rikby/gitext: It looks like there are no installed required packages. '
        . 'Please run "composer install" within composer directory.');
}

/** @return Composer\Autoload\ClassLoader */
return require $dir . '/autoload.php';
