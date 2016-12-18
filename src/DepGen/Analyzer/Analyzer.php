<?php
/**
 * @author BVengerov
 * @description Analyzer of dependencies
 * TODO Add support of something more than just Adts
*/

namespace DepGen\Analyzer;

use DepGen\ChangedEntities\ChangedEntity;
use DepGen\Adt;

class Analyzer
{
    public static function generateAffectedEntities($changedEntities, $graph)
    {
        $projectAdts = self::_arrayFlatten($graph);
        $affectedAdts = array_map(
            function (ChangedEntity $changedEntity) { return new Adt($changedEntity->getFilename()); },
            $changedEntities
        );

        $adtsToCheck = $affectedAdts;
        // Check inheritance in cycles for found affected adts
        while (true)
        {
            $affectedAdtsPortion = [];

            foreach ($projectAdts as $projectAdt)
            {
                foreach ($adtsToCheck as $adtToCheck)
                    {
                        //TODO add only absent adts to affected adts
                        if ($projectAdt->getParentFqan() === $adtToCheck->getFqan())
                        {
                            $affectedAdtsPortion[] = $projectAdt;
                        }
                    }
            }

            if (count($affectedAdtsPortion) > 0)
            {
                $affectedAdts = array_merge($affectedAdts, $affectedAdtsPortion);
                $adtsToCheck = $affectedAdtsPortion;
            }
            else
            {
                return $affectedAdts;
            }
        }
    }

    private static function _arrayFlatten($array) {
        $result = array();
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $result = array_merge($result, self::_arrayFlatten($value));
            }
            else
            {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}