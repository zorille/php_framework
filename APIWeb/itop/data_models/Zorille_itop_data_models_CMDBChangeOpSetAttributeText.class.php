<?php

namespace Zorille\itop\data_models;

class CMDBChangeOpSetAttributeText extends CMDBChangeOp
{
	const ENTITY_NAME = 'CMDBChangeOpSetAttributeText';

	protected string $attcode = '';
	protected string $prevdata = '';
	protected string $finalclass = 'CMDBChangeOpSetAttributeText';
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

	public function getPrevdata(): string
	{
		return $this->prevdata;
	}
	public function setPrevdata(string $prevdata): static
	{
		$this->prevdata = $prevdata;
		return $this;
	}

}
