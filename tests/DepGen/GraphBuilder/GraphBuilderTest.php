<?php
/**
 * @author BVengerov
 * @description Tests on GraphBuilder
 */

namespace Tests\DepGen\GraphBuilder;

use DepGen\GraphBuilder\GraphBuilder;

class GraphBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function testGetPhpFilesInDir()
	{
		$sut = new GraphBuilder();
		$result = $sut->getPhpFilesInDir(__DIR__);

		$this->assertContains(
			__FILE__,
			$result,
			'The expected filename is not present in the collected files list.'
		);
	}

	public function testBuildNamespaceGraph()
	{
		$sut = new GraphBuilder();
		$result = $sut->buildNamespaceGraph(__DIR__);

		$fqcnParts = explode("\\", __CLASS__);
		$className = array_pop($fqcnParts);

		$currentNode = $result;
		foreach ($fqcnParts as $namespacePart)
		{
			if (isset($currentNode[$namespacePart]))
				$currentNode = $currentNode[$namespacePart];
			else
				throw new \PHPUnit_Framework_ExpectationFailedException(
					"Namespace part $namespacePart is not present in result: " . var_export($result, true)
				);
		}

		$this->assertContains(
			$className,
			$currentNode,
			'The expected class name is not present in the built namespace graph.'
		);
	}
}