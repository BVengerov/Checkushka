<?php
/**
 * @author BVengerov
 * @description Creates ChangedEntity objects from provided data
 * TODO maybe add support of non-existent files
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
			if (self::_checkFileExists($stdClass->fileName))
				$changedEntities[] = new ChangedEntity(
					$stdClass->fileName,
					$stdClass->entityType,
					$stdClass->changeType
				);
		}
		return $changedEntities;
	}

	public function generateFromFilesString($filesString)
	{
		$fileNames = explode(',', $filesString);
		$result = [];
		foreach ($fileNames as $fileName)
		{
			if (self::_checkFileExists($fileName))
			{
				$result[] = new ChangedEntity($fileName, 'file', 'edited');
			}
		}
		return $result;
	}

	private static function _checkFileExists($fileName)
	{
		$fileExists = file_exists($fileName);
		if (!$fileExists)
			echo("\n[WARNING] File $fileName doesn't exist, ignoring.\n");
		return $fileExists;
	}
}
