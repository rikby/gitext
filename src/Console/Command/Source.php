<?php

namespace Rikby\GitExt\Console\Command;

use Rikby\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for showing source files path
 *
 * @package PreCommit
 */
class Source extends AbstractCommand
{
    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            str_replace(
                '\\',
                '/',
                realpath(__DIR__.'/../..')
            )
        );

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureCommand()
    {
        $this->setName('source');

        $this->setHelp('Show GitExt package source files path.');
        $this->setDescription('Show GitExt package source files path.');
    }
}
