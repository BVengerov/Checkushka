<?php
/**
 * @author BVengerov
 * @description Command for generating dependencies for provided entities
 */

namespace DepGen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
			->setName('generate')
			->setDescription('Generates dependecies for the provided input')
			->setHelp(
				'This commands outputs entities dependent on the provided input.'
			);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('This command is yet to be implemented.');
    }
}
