<?php
/**
 * Sort versions list
 *
 * Add an alias in GIT
 *      $ git config --global alias.tags "!git tag | xargs -i -0 php ~/.version_sort.php {}"
 */

use Naneau\SemVer\Sort;

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

    $sorted = Sort::sortArray($versions);

//    array_walk($versions, 'trim');
//    usort($versions, 'version_compare');
    echo implode(PHP_EOL, $sorted);
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
    exit(1);
}
