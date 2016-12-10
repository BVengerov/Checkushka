<?php
/**
 * @author BVengerov
 * @description Generates graph tree by namespaces according to PSR-4
 * !Doesn't support traits for now
 */

namespace DepGen\GraphBuilder;

class GraphBuilder
{
	/** @var array */
	private $_graph;

	public function __construct()
	{
		$this->_graph = [];
	}

	public function buildNamespaceGraph($dir)
	{
		//Collecting abstract data types (classes and traits)
		$adts = [];
		foreach ($this->getPhpFilesInDir($dir) as $fileName)
		{
			$adt = new Adt($fileName);
			if (!is_null($adt->isAdt()))
				$adts[] = $adt;
		}

		foreach ($adts as $adt)
		{
			$this->_addAdtToGraph($adt);
		}

		return $this->_graph;
	}

	public function getPhpFilesInDir($dir)
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

	private function _addAdtToGraph(Adt $adt)
	{
		$currentNode = &$this->_graph;

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
