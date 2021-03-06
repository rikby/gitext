<?php
// This file is not a CODE, it makes no sense and won't run or validate
// Its AST serves PHPStorm IDE as DATA source to make advanced type inference decisions.
// @codingStandardsIgnoreFile
namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        \Symfony\Component\Console\Helper\HelperSet::get('') => [
            'php_bin_get' instanceof \Rikby\Console\Helper\PhpBinHelper,
            'simple_question' instanceof \Rikby\Console\Helper\SimpleQuestionHelper,
            'git_dir_get' instanceof \Rikby\Console\Helper\GitDirHelper,
            'shell' instanceof \Rikby\Console\Helper\Shell\ShellHelper,
            'question' instanceof \Symfony\Component\Console\Helper\SymfonyQuestionHelper,
            'process' instanceof \Symfony\Component\Console\Helper\ProcessHelper,
            'debug_formatter' instanceof \Symfony\Component\Console\Helper\DebugFormatterHelper,
            'descriptor' instanceof \Symfony\Component\Console\Helper\DescriptorHelper,
        ],
        \Symfony\Component\Console\Helper\HelperSet::has('') => [
            'php_bin_get' instanceof \SplBool,
            'simple_question' instanceof \SplBool,
            'git_dir_get' instanceof \SplBool,
            'shell' instanceof \SplBool,
            'question' instanceof \SplBool,
            'process' instanceof \SplBool,
            'debug_formatter' instanceof \SplBool,
            'descriptor' instanceof \SplBool,
        ],
    ];
}
