<?php
// @codingStandardsIgnoreFile

/**
 * Sort versions list
 */
require_once __DIR__.'/../../../bin/autoload-init.php';
//use Naneau\SemVer\Sort;

try {
    if (php_sapi_name() != 'cli') {
        throw new Exception('This file can be run in CLI only.');
    }
    //remove file from the args
    $serverArgs = (array) $_SERVER['argv'];
    array_shift($serverArgs);

    if (!$serverArgs) {
        echo 'error: No argument/s. Please set version/s as argument/s.';
        exit(1);
    }

    $versions = preg_split(
        "(\n|\s)",
        implode(' ', $serverArgs)
    );

    $originalVersions = [];
    foreach ($versions as $key => $original) {
        $normal = ltrim($original, 'v');

        $originalVersions[$normal] = $original;

        $versions[$key] = $normal;
    }

    /**
     * Cannot use naneau/semver to to a problem with patches.
     * But it looks like SemVer doesn't describe patches at all on http://semver.org.
     *
     * @link https://github.com/naneau/semver/issues/24
     */
//    $versions = call_user_func_array(Sort::class . '::sort', $versions);

    array_walk($originalVersions, 'trim');
    usort(
        $originalVersions,
        function ($a, $b) {
            list($a) = explode('+', $a);
            list($b) = explode('+', $b);

            return version_compare($a, $b);
        }
    );
    $versions = $originalVersions;

    echo implode(PHP_EOL, $versions);
} catch (Exception $e) {
    echo 'error: '.$e->getMessage();
    exit(1);
}
