<?php
/**
 * @author BVengerov
 * @description Tests on GenerateCommand class
 */

namespace Tests\DepGen\Command;

use Tests\BaseTest;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use DepGen\Command\GenerateCommand;

//TODO think about how (and whether) to test this!
abstract class GenerateCommandTest extends BaseTest
{
	// public function testFileOptionExists()
	// {
	// 	$application = new Application('DepGen');

	// 	$application->add(new GenerateCommand());

	// 	$command = $application->find('generate');
	// 	$commandTester = new CommandTester($command);
	// 	try {
	// 		$commandTester->execute([
	// 			'command' => $command->getName(),
	// 			'--file' => 'some_test_file'
	// 		]);
	// 	} catch (\Exception $e) {
	// 		throw new \PHPUnit_Framework_AssertionFailedError(
	// 			"Executing command with --file option failed with message:\n"
	// 			. $e->getMessage()
	// 		);
	// 	}
	// }
}
