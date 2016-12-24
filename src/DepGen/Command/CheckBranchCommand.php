<?php
/**
 * @author BVengerov
 * @description Command for generating dependencies for all changes in a git branch
 */

namespace DepGen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\ArrayInput;

class CheckBranchCommand extends Command
{
	const NAME = 'check-branch';
	const ARG_PROJECT_DIR = 'project_dir';

    protected function configure()
    {
        $this
			->setName(self::NAME)
			->setDescription('Generates dependecies for changes made in current branch.')
			->setHelp('Generates dependecies for all changes made in the current git branch.')
			->addUsage(self::NAME . ' /path/to/project')
			->addUsage(self::NAME . ' \"$PWD\"');

		$this->addArgument(
			self::ARG_PROJECT_DIR,
			InputArgument::OPTIONAL,
			'The directory in which to perform analysis'
		);
    }

	protected function interact(InputInterface $input, OutputInterface $output)
	{
		// Ask for project dir argument value if it hasn't been provided
		if (!$input->getArgument(self::ARG_PROJECT_DIR))
		{
			/** @var QuestionHelper $helper */
			$helper = $this->getHelper('question');
			$question = new Question('<comment>Please specify the directory with the project to analyze:</comment> ', false);
			$projectDir = $helper->ask($input, $output, $question);

			if (!$projectDir)
			{
				$output->writeln('Project dir has not been specified, exiting.');
				throw new \Exception('Project dir is the required argument.');
			}
			else
			{
				$input->setArgument(self::ARG_PROJECT_DIR, $projectDir);
			}
		}
	}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$fileLines = shell_exec(ROOT_DIR . '/src/Differ/scripts/git_diff_branch.sh ' . $input->getArgument(self::ARG_PROJECT_DIR));
		$fileNames = str_replace("\n", ',', rtrim($fileLines, "\n"));

		$output->writeln("Found changed files:\n$fileNames\n");

		$generateCommand = $this->getApplication()->find(GenerateCommand::NAME);
		$arguments = [
			'command' => GenerateCommand::NAME,
			GenerateCommand::ARG_PROJECT_DIR => $input->getArgument(self::ARG_PROJECT_DIR),
			'--files' => $fileNames
		];
		$generateInput = new ArrayInput($arguments);

		$output->writeln("Generating dependencies...\n");
		$returnCode = $generateCommand->run($generateInput, $output);
    }
}
