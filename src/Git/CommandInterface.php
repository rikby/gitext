<?php
namespace Rikby\GitExt\Git;

/**
 * Interface CommandInterface
 *
 * @package Rikby\GitExt\Git
 */
interface CommandInterface
{
    /**#@+
     * Command file meta tags
     */
    const TAG_DESCRIPTION = 'DESCR';
    const TAG_CMD         = 'CMD';
    /**#@-*/

    /**
     * Command constructor.
     *
     * @param string $file
     */
    public function __construct($file);

    /**
     * Get command exec file
     *
     * @return string
     */
    public function file();

    /**
     * Get command description
     *
     * @return string
     */
    public function command();

    /**
     * Get command alias
     *
     * @return string
     */
    public function alias();

    /**
     * Get command description
     *
     * @param string $indent
     * @return string
     */
    public function description($indent = '');
}
