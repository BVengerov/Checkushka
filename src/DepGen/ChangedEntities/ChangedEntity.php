<?php
/**
 * @author BVengerov
 * @description An abstraction for a changed entity (i.e. class, function, property)
 * !Only "class" is supported as entity type for now.
 * !Only "edited" is supported as change type for now.
 */

namespace DepGen\ChangedEntities;

class ChangedEntity
{
	private $_name;
	private $_fileName;
	private $_entityType;
	private $_changeType;

	public function __construct($name, $fileName, $entityType, $changeType)
	{
		$this->_name = $name;
		$this->_fileName = $fileName;
		$this->_entityType = $entityType;
		$this->_changeType = $changeType;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getFileName()
	{
		return $this->_fileName;
	}

	public function getEntityType()
	{
		return $this->_entityType;
	}

	public function getChangeType()
	{
		return $this->_changeType;
	}
}
