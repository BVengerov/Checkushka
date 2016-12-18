<?php
/**
 * @author BVengerov
 * @description Command for generating dependencies for provided entities
 */

namespace DepGen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use DepGen\Analyzer\Analyzer;
use DepGen\ChangedEntities\ChangedEntitiesGenerator;
use DepGen\GraphBuilder\GraphBuilder;

class GenerateCommand extends Command
{
	const NAME = 'generate';
	const ARG_PROJECT_DIR = 'project_dir';
	const OPT_JSON = 'json';
	const OPT_FILES = 'files';

    protected function configure()
    {
        $this
			->setName(self::NAME)
			->setDescription('Generates dependecies for the provided input')
			->setHelp('This commands outputs entities dependent on the provided input.')
			->addUsage('generate -f=/path/to/json/ /path/to/project');
		$this->addArgument(
			self::ARG_PROJECT_DIR,
			InputArgument::OPTIONAL,
			'The directory in which to perform analysis'
		);
		$this->addOption(
			self::OPT_JSON,
			'j',
			InputOption::VALUE_OPTIONAL,
			'JSON file with a list of changed entities'
		);
		$this->addOption(
			self::OPT_FILES,
			'f',
			InputOption::VALUE_OPTIONAL,
			'JSON file with a list of changed entities'
		);
    }

	protected function interact(InputInterface $input, OutputInterface $output)
	{
		if (!$input->getOption(self::OPT_JSON) && !$input->getOption(self::OPT_FILES))
			throw new \Exception('Either of --' . self::OPT_JSON . ' or --' . self::OPT_FILES . ' options needs to be specified!');
		elseif ($input->getOption(self::OPT_JSON) && $input->getOption(self::OPT_FILES))
			throw new \Exception('You cannnot specify both --' . self::OPT_JSON . ' and --' . self::OPT_FILES . ' options');
	}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		if ($input->getOption(self::OPT_JSON))
		{
			$json = file_get_contents($input->getOption(self::OPT_JSON));
			$changedEntities = ChangedEntitiesGenerator::generateFromJson($json);
		}
		else
		{
			$changedEntities = ChangedEntitiesGenerator::generateFromFilesString($input->getOption(self::OPT_FILES));
		}
    	$graph = GraphBuilder::buildNamespaceGraph($input->getArgument(self::ARG_PROJECT_DIR));
    	$affectedEntities = Analyzer::generateAffectedEntities($changedEntities, $graph);
		$classList = array_map(
			function ($affectedEntity) { return $affectedEntity->getFqan(); },
			$affectedEntities
		);
		$count = count($affectedEntities);
		$output->writeln("\nList of fully qualified class names of all affected classes ($count items):\n");
		$output->writeln(implode(',', $classList));
    }
}
