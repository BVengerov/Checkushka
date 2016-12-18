<?php
/**
 * @author BVengerov
 * @description Tests on Adt class
*/

namespace Tests\DepGen;

use Tests\BaseTest;
use DepGen\Adt;

class AdtTest extends BaseTest
{
    public function testClassInfoParsedCorrectly()
    {
        $adt = new Adt(__FILE__);

        $this->assertTrue(
            $adt->isAdt(),
            'Filename of the created Adt object differs from expected.'
        );
        $this->assertEquals(
            __FILE__,
            $adt->getFileName(),
            'Filename of the created Adt object differs from expected.'
        );
        $classParts = explode('\\', self::class);
        $this->assertEquals(
            array_pop($classParts),
            $adt->getName(),
            'Name of the created Adt object differs from expected.'
        );
        $this->assertEquals(
            self::class,
            $adt->getFqan(),
            'Name of the created Adt object differs from expected.'
        );
        $this->assertEquals(
            explode('\\', __NAMESPACE__),
            $adt->getNamespace(),
            'Namespace of the created Adt object differs from expected.'
        );
        $this->assertEquals(
            'Tests\BaseTest',
            $adt->getParentFqan(),
            'Parent fully qualified adt name of the created Adt object differs from expected.'
        );
    }

    public function testParentFqanForClassInSameNamespace()
    {
        $adt = new Adt(self::TEST_PROJECT_DIR . '/Inheritance/FolderWithBaseClass/ChildInSameNamespace.php');
        $this->assertEquals('Koshki\\Inheritance\\FolderWithBaseClass\\BaseClass', $adt->getParentFqan());
    }
}