<?php
namespace Rikby\GitExt\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 *
 * @package RikbyGitExt\Command
 */
class Application extends BaseApplication
{
    /**
     * Version
     *
     * Please update also src/config.xml
     *
     * @see src/config.xml
     */
    const VERSION = '0.x-dev';

// @codingStandardsIgnoreStart
    /**
     * Logo
     *
     * @var string
     */
    protected $logo
        = <<<LOGO
   _____ _ _   ______      _   
  / ____(_) | |  ____|    | |  
 | |  __ _| |_| |__  __  _| |_ 
 | | |_ | | __|  __| \ \/ / __|
 | |__| | | |_| |____ >  <| |_ 
  \_____|_|\__|______/_/\_\\\\__|

LOGO;
// @codingStandardsIgnoreEnd

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct(
            'GitExtra commands',
            self::VERSION
        );
    }

    /**
     * Get help
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->logo.parent::getHelp();
    }
}
