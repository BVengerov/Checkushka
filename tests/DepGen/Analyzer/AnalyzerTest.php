<?php
/**
 * @author BVengerov
 * @description Tests on Analyzer class
*/

namespace Tests\DepGen\Analyzer;

use Tests\BaseTest;
use DepGen\GraphBuilder\GraphBuilder;
use DepGen\Analyzer\Analyzer;
use DepGen\ChangedEntities\ChangedEntitiesGenerator;

class AnalyzerTest extends BaseTest
{
    public function testInheritance()
    {
        $jsonString =
        '[{
            "fileName": "' . self::TEST_PROJECT_DIR . '/Inheritance/FolderWithBaseClass/BaseClass.php",
            "entityType": "class",
            "changeType": "edited"
        }]';
		$changedEntities = ChangedEntitiesGenerator::generateFromJson($jsonString);
        $graph = GraphBuilder::buildNamespaceGraph(self::TEST_PROJECT_DIR . '/Inheritance');

    	$affectedEntities = Analyzer::generateAffectedEntities($changedEntities, $graph);

        $this->assertCount(3, $affectedEntities, 'Expected affected entities count is not as expected.');
    }
}