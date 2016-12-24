<?php
/**
 * @author BVengerov
 * @description Generates graph tree by namespaces according to PSR-4
 * TODO add traits support
 */

namespace DepGen\GraphBuilder;

use DepGen\Adt;

class GraphBuilder
{
	public static function buildNamespaceGraph($dir)
	{
		$graph = [];

		//Collecting abstract data types (classes and traits)
		$adts = [];
		foreach (self::getPhpFilesInDir($dir) as $fileName)
		{
			$adt = new Adt($fileName);
			if ($adt->isAdt())
				$adts[] = $adt;
		}

		foreach ($adts as $adt)
		{
			self::_addAdtToGraph($adt, $graph);
		}

		return $graph;
	}

	public static function getPhpFilesInDir($dir)
	{
		$directory = new \RecursiveDirectoryIterator($dir);
		$iterator = new \RecursiveIteratorIterator($directory);
		$regexIterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

		$files = [];
		foreach($regexIterator as $element)
		{
			$files[] = $element[0];
		}
		return $files;
	}

	private static function _addAdtToGraph(Adt $adt, &$graph)
	{
		$currentNode = &$graph;

		foreach ($adt->getNamespace() as $namespacePart)
		{
			if ($namespacePart === '')
				continue;

			if (!isset($currentNode[$namespacePart]))
				$currentNode[$namespacePart] = [];

			$currentNode = &$currentNode[$namespacePart];
		}
		$currentNode[] = $adt;
	}
}
