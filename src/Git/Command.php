<?php

namespace Rikby\GitExt\Git;

/**
 * Git Command class
 *
 * @package Rikby\GitExt\Git
 */
class Command implements CommandInterface
{
    /**
     * Content of command file
     *
     * @var string
     */
    protected $content;

    /**
     * Command file
     *
     * @var string
     */
    protected $file;

    /**
     * Command constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Get command description
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Get command description
     *
     * @return string
     * @throws \Rikby\GitExt\Git\Exception
     */
    public function command()
    {
        $cmd = $this->singleMeta(self::TAG_CMD);
        if (!$cmd) {
            $cmd = str_replace(
                'git-',
                'git ',
                pathinfo($this->file(), PATHINFO_FILENAME)
            );
        }
        if (!$cmd) {
            throw new Exception(
                'Empty command line. Please add line "# CMD: git your-command" in file %s.',
                $this->file()
            );
        }

        return $cmd;
    }

    /**
     * Get command description
     *
     * @param string $indent
     * @return string
     */
    public function description($indent = '')
    {
        return $indent.implode("\n$indent", $this->meta(self::TAG_DESCRIPTION));
    }

    /**
     * Get GIT alias
     *
     * @return string
     */
    public function alias()
    {
        $alias = str_replace('git-', '', $this->command());

        return str_replace('git ', '', $alias);
    }

    /**
     * Read command meta info
     *
     * @param string $tag
     * @return array
     */
    protected function meta($tag)
    {
        preg_match_all(
            '~\#\s*'.$tag.':\s*(.+)~',
            $this->content(),
            $matches
        );

        list(, $matches) = $matches;

        if (!$matches) {
            return [];
        }

        return $matches;
    }

    /**
     * Read command single meta info
     *
     * Get only first match.
     *
     * @param string $tag
     * @return string
     */
    protected function singleMeta($tag)
    {
        preg_match(
            '~\#\s*'.$tag.':\s*(.+)~',
            $this->content(),
            $matches
        );
        if (!$matches) {
            return '';
        }

        list(, $matches) = $matches;

        return trim($matches);
    }

    /**
     * Get command file content
     *
     * @return string
     */
    protected function content()
    {
        if ($this->content === null) {
            $this->content = file_get_contents($this->file);
        }

        return $this->content;
    }
}
