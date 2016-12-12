<?php
/**
 * @author BVengerov
 * @description Abstract data type (node) in the project graph
 */

namespace DepGen\GraphBuilder;

use PhpParser\ParserFactory;

class Adt
{
	private $_fileName;
	private $_namespaceParts;
	private $_name;
	private $_parentFqcnParts;

	public function __construct($fileName)
	{
		$this->_fileName = $fileName;

		$code = file_get_contents($fileName);
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$stmts = $parser->parse($code);

		$this->_namespaceParts = $this->_getNamespaceFromStatements($stmts);
		$this->_name = $this->_getNameFromStatements($stmts);
		$this->_parentFqcnParts = $this->_getParentFromStatements($stmts);
	}

	public function getFileName()
	{
		return $this->_fileName;
	}

	public function getNamespace()
	{
		return $this->_namespaceParts;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function isAdt()
	{
		return !is_null($this->_name) && !is_null($this->_namespaceParts);
	}

	private function _getNamespaceFromStatements($stmts)
	{
		return $stmts[0]->name->parts;
	}

	private function _getNameFromStatements($stmts)
	{
		$statements = $stmts[0]->stmts;
		$classStmts = $statements[count($statements) - 1];
		return $classStmts->name;
	}

	private function _getParentFromStatements($stmts)
	{
		$statements = $stmts[0]->stmts;
		$classStmts = array_pop($statements);

		if (!isset($classStmts->extends))
			return null;

		$extendsParts = $classStmts->extends->parts;

		$usedAliases = [];
		foreach ($statements as $statement)
		{
			$usedAliases[$statement->uses[0]->alias] = $statement->uses[0]->name->parts;
		}

		$firstExtendsPart = $extendsParts[0];

		// If the first "extends" statement part is in the "use" statements aliases, then we build fqcn with the namespace parts
		if (array_key_exists($firstExtendsPart, $usedAliases))
		{
			$parentFqcnParts = $usedAliases[$firstExtendsPart];
			array_pop($parentFqcnParts);
		}
		// If the first part is absent in "use" statements, but a file with corresponding name exists, then it is in the same namespace
		elseif ($this->_isNameInDir($firstExtendsPart, dirname($this->getFileName())))
		{
			$parentFqcnParts = $this->getNamespace();
		}
		// Else the name is in the global namespace
		else
		{
			$parentFqcnParts = ['\\'];
		}

		return array_merge($parentFqcnParts, $extendsParts);
	}

	private function _isNameInDir($name, $path)
	{
		return count(glob("{$path}*{$name}*")) > 0;
	}
}