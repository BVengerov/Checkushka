<?php
/**
 * @author BVengerov
 * @description Tests on ChangedEntitiesGenerator
 */

namespace Tests\DepGen\ChangedEntities;

use DepGen\ChangedEntities\ChangedEntitiesGenerator;

class ChangedEntitiesGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGenerateFromJson()
	{
		$jsonString =
				'[{
					"name": "SomeClass",
					"fileName": "/some/file/name.php",
					"entityType": "class",
					"changeType": "edited"
				},
				{
					"name": "SomeOtherClass",
					"fileName": "/some/other/file/name.php",
					"entityType": "class",
					"changeType": "edited"
				}]';
		$result = ChangedEntitiesGenerator::generateFromJson($jsonString);
		$changedEntity0 = $result[0];
		$this->assertEquals(
			'SomeClass',
			$changedEntity0->getName(),
			'Entity with index 0 was generated with incorrect name'
		);
		$this->assertEquals(
			'/some/file/name.php',
			$changedEntity0->getFileName(),
			'Entity with index 0 was generated with incorrect file name'
		);
		$this->assertEquals(
			'class',
			$changedEntity0->getEntityType(),
			'Entity with index 0 was generated with incorrect entity type'
		);
		$this->assertEquals(
			'edited',
			$changedEntity0->getChangeType(),
			'Entity with index 0 was generated with incorrect change type'
		);
		$changedEntity1 = $result[1];
		$this->assertEquals(
			'SomeOtherClass',
			$changedEntity1->getName(),
			'Entity with index 1 was generated with incorrect name'
		);
		$this->assertEquals(
			'/some/other/file/name.php',
			$changedEntity1->getFileName(),
			'Entity with index 1 was generated with incorrect file name'
		);
		$this->assertEquals(
			'class',
			$changedEntity1->getEntityType(),
			'Entity with index 1 was generated with incorrect entity type'
		);
		$this->assertEquals(
			'edited',
			$changedEntity1->getChangeType(),
			'Entity with index 1 was generated with incorrect change type'
		);
	}
}
