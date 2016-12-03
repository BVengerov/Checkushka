<?php
/**
 * @author BVengerov
 * @description Creates ChangedEntity objects from provided data
 */

namespace DepGen\ChangedEntities;

class ChangedEntitiesGenerator
{
	public static function generateFromJson($jsonString)
	{
		$data = json_decode($jsonString);

		$changedEntities = [];
		foreach ($data as $stdClass)
		{
			$changedEntities[] = new ChangedEntity(
				$stdClass->name,
				$stdClass->fileName,
				$stdClass->entityType,
				$stdClass->changeType
			);
		}
		return $changedEntities;
	}
}
