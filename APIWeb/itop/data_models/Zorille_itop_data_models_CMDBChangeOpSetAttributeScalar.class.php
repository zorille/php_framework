<?php

namespace Zorille\itop\data_models;

class CMDBChangeOpSetAttributeScalar extends CMDBChangeOp
{
	const ENTITY_NAME = 'CMDBChangeOpSetAttributeScalar';

	protected string $attcode = '';
	protected string $oldvalue = '';
	protected string $newvalue = '';
	protected string $finalclass = 'CMDBChangeOpSetAttributeScalar';
	protected ?string $friendlyname = null;

	public function getAttcode(): string
	{
		return $this->attcode;
	}
	public function setAttcode(string $attcode): static
	{
		$this->attcode = $attcode;
		return $this;
	}

	public function getOldvalue(): string
	{
		return $this->oldvalue;
	}
	public function setOldvalue(string $oldvalue): static
	{
		$this->oldvalue = $oldvalue;
		return $this;
	}

	public function getNewvalue(): string
	{
		return $this->newvalue;
	}
	public function setNewvalue(string $newvalue): static
	{
		$this->newvalue = $newvalue;
		return $this;
	}
}
