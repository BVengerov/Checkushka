<?php
/**
 * @author BVengerov
 * @description Tests on ChangedEntitiesGenerator
 */

namespace Tests\DepGen\ChangedEntities;

use Tests\BaseTest;
use DepGen\ChangedEntities\ChangedEntitiesGenerator;

class ChangedEntitiesGeneratorTest extends BaseTest
{
	public function testGenerateFromJson()
	{
		$jsonString =
				'[{
					"fileName": "' . self::TEST_PROJECT_DIR . '/Inheritance/FolderWithBaseClass/UnrelatedClassInSameNamespace.php",
					"entityType": "class",
					"changeType": "edited"
				},
				{
					"fileName": "' . self::TEST_PROJECT_DIR . '/Inheritance/SomeOtherFolder/UnrelatedClassInOtherNamespace.php",
					"entityType": "class",
					"changeType": "edited"
				}]';
		$result = ChangedEntitiesGenerator::generateFromJson($jsonString);

		$changedEntity0 = $result[0];
		$this->assertEquals(
			self::TEST_PROJECT_DIR . '/Inheritance/FolderWithBaseClass/UnrelatedClassInSameNamespace.php',
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
			self::TEST_PROJECT_DIR . '/Inheritance/SomeOtherFolder/UnrelatedClassInOtherNamespace.php',
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
