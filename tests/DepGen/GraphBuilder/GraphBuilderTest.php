<?php
/**
 * @author BVengerov
 * @description Tests on GraphBuilder
 */

namespace Tests\DepGen\GraphBuilder;

use DepGen\GraphBuilder\GraphBuilder;
use DepGen\GraphBuilder\Adt;

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

		$classesFoundByExactName = 0;
		foreach ($currentNode as $i => $element)
		{
			$this->assertTrue(
				$element instanceOf Adt,
				"Element $i is not an instance of Adt class: " . var_export($element, true)
			);

			if ($element->getName() === $className)
				$classesFoundByExactName++;
		}

		$this->assertEquals(
			1,
			$classesFoundByExactName,
			"There is not exactly one class with name {$className}: " . var_export($result, true)
		);
	}
}