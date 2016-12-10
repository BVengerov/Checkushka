<?php
/**
 * @author BVengerov
 * @description Command for generating dependencies for provided entities
 */

namespace DepGen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
	const NAME = 'generate';
	const OPT_FILE = 'file';

    protected function configure()
    {
        $this
			->setName(self::NAME)
			->setDescription('Generates dependecies for the provided input')
			->setHelp(
				'This commands outputs entities dependent on the provided input.'
			);
		$this->addOption(
			self::OPT_FILE,
			'f',
			InputOption::VALUE_REQUIRED,
			'File with input in JSON for which to generate dependencies',
			null
		);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$output->writeln('This command is yet to be implemented.');
    }
}
