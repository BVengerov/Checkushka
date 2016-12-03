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
		//Collecting abstract data types (classes and traits) as a namespace tree
		foreach ($this->getPhpFilesInDir($dir) as $file)
		{
			$this->_addAdtToGraph($file);
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

	private function _addAdtToGraph($fileName)
	{
		$lines = file($fileName);

		$namespace = $this->_getNamespaceFromFileLines($lines);
		if (!$namespace)
			return;

		$adtName = $this->_getAdtNameFromFileLines($lines);
		if (!$adtName)
			return;

		$currentNode = &$this->_graph;
		foreach (explode("\\", $namespace) as $namespacePart)
		{
			if ($namespacePart === '')
				continue;

			if (!isset($currentNode[$namespacePart]))
				$currentNode[$namespacePart] = [];

			$currentNode = &$currentNode[$namespacePart];
		}

		$currentNode[] = $adtName;
	}

	private function _getNamespaceFromFileLines($fileLines)
	{
		$namespaceGrep = preg_grep('/^namespace /', $fileLines);
		$namespaceLine = array_shift($namespaceGrep);
		preg_match('/^namespace (.*);$/', $namespaceLine, $match);
		$namespace = array_pop($match);
		return $namespace;
	}

	private function _getAdtNameFromFileLines($fileLines)
	{
		//TODO add trait support
		$adtGrep = preg_grep('/^class /', $fileLines);
		$adtLine = array_shift($adtGrep);
		preg_match('/class\s+([^\s{]+)/', $adtLine, $match);
		$adtName = array_pop($match);
		return $adtName;
	}
}
