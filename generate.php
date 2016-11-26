#!/usr/bin/env php
<?php
/**
 * @author BVengerov
 * @description The script for using DepGen
 */

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use DepGen\Command\GenerateCommand;

$application = new Application();

$application->add(new GenerateCommand());

$application->run();
