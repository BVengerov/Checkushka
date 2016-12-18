<?php
/**
 * @author BVengerov
 * @description Abstract data type (node) in the project graph
 */

namespace DepGen;

use PhpParser\ParserFactory;

class Adt
{
	private $_fileName;
	private $_namespaceParts;
	private $_name;
	private $_fqan;
	private $_parentFqan;

	public function __construct($fileName)
	{
		$this->_fileName = $fileName;

		$code = file_get_contents($fileName);
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$stmts = $parser->parse($code);

		$this->_namespaceParts = $this->_getNamespaceFromStatements($stmts);
		$this->_name = $this->_getNameFromStatements($stmts);
		$this->_fqan = implode('\\', $this->_namespaceParts) . '\\' . $this->_name;
		$this->_parentFqan = $this->_getParentFqanFromStatements($stmts);
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

	public function getFqan()
	{
		return $this->_fqan;
	}

	public function isAdt()
	{
		return !is_null($this->_name) && !is_null($this->_namespaceParts);
	}

	public function getParentFqan()
	{
		return $this->_parentFqan;
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

	private function _getParentFqanFromStatements($stmts)
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

		// If the first "extends" statement part is in the "use" statements aliases, then we build Fqan with the namespace parts
		if (array_key_exists($firstExtendsPart, $usedAliases))
		{
			$parentFqanParts = $usedAliases[$firstExtendsPart];
			array_pop($parentFqanParts);
		}
		// If the first part is absent in "use" statements, but a file with corresponding name exists, then it is in the same namespace
		elseif ($this->_isNameInDir($firstExtendsPart, dirname($this->getFileName())))
		{
			$parentFqanParts = $this->getNamespace();
		}
		// Else the name is in the global namespace
		else
		{
			$parentFqanParts = ['\\'];
		}

		return implode('\\', array_merge($parentFqanParts, $extendsParts));
	}

	private function _isNameInDir($name, $path)
	{
		return count(glob("{$path}/*{$name}*")) > 0;
	}
}