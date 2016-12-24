#!/usr/bin/env php
<?php
/**
 * @author BVengerov
 * @description The script for using DepGen
 */

require __DIR__ . '/vendor/autoload.php';

if (!defined('ROOT_DIR'))
{
	define('ROOT_DIR', __DIR__);
}

use Symfony\Component\Console\Application;
use DepGen\Command\GenerateCommand;
use DepGen\Command\CheckBranchCommand;

$application = new Application('DepGen');

$application->add(new GenerateCommand());
$application->add(new CheckBranchCommand());

$application->run();
