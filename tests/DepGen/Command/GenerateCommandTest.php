<?php
/**
 * @author BVengerov
 * @description Tests on GenerateCommand class
 */

namespace Test\DepGen\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use DepGen\Command\GenerateCommand;

class GenerateCommandTest extends \PHPUnit_Framework_TestCase
{
	public function testFileOptionExists()
	{
		$application = new Application('DepGen');

		$application->add(new GenerateCommand());

		$command = $application->find('generate');
		$commandTester = new CommandTester($command);
		try {
			$commandTester->execute([
				'command' => $command->getName(),
				'--file' => 'some_test_file'
			]);
		} catch (\Exception $e) {
			throw new \PHPUnit_Framework_AssertionFailedError(
				"Executing command with --file option failed with message:\n"
				. $e->getMessage()
			);
		}
	}
}
