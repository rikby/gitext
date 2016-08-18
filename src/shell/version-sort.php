<?php
// @codingStandardsIgnoreFile

/**
 * Sort versions list
 */
require_once __DIR__ . '/../../bin/autoload-init.php';
//use Naneau\SemVer\Sort;

try {
    if (php_sapi_name() != 'cli') {
        throw new Exception('This file can be run in CLI only.');
    }
    if ($_SERVER['argc'] < 2) {
        echo 'error: Invalid argument. Please set version/s as argument/s.';
        exit(1);
    }

    if ($_SERVER['argc'] > 2) {
        $versionsRaw = implode(' ', $_SERVER['argv']);
    } else {
        $versionsRaw = trim($_SERVER['argv'][1]);
    }

    $versions = preg_replace('#\+[^\n]+#', '', $versionsRaw); //Remove meta information after sign "+"
    $versions = explode("\n", $versions);

    $originalVersions = [];
    foreach ($versions as $key => $original) {
        $normal = ltrim($original, 'v');

        $originalVersions[$normal] = $original;

        $versions[$key] = $normal;
    }

    /**
     * Cannot use naneau/semver to to a problem with patches
     * @link https://github.com/naneau/semver/issues/24
     */
//    $versions = call_user_func_array(Sort::class . '::sort', $versions);

    array_walk($originalVersions, 'trim');
    usort($originalVersions, 'version_compare');
    $versions = $originalVersions;

    echo implode(PHP_EOL, $versions);
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
    exit(1);
}
