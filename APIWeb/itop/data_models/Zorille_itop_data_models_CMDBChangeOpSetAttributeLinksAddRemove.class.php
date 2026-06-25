<?php

namespace Zorille\itop\data_models;

class CMDBChangeOpSetAttributeLinksAddRemove extends CMDBChangeOp
{
	const ENTITY_NAME = 'CMDBChangeOpSetAttributeLinksAddRemove';

	protected string $attcode = '';
	protected string $item_class = '';
	protected string $item_id = '';
	protected CMDBChangeOpSetAttributeLinksAddRemoveType $type = CMDBChangeOpSetAttributeLinksAddRemoveType::ADDED;
	protected string $finalclass = 'CMDBChangeOpSetAttributeLinksAddRemove';

	public function getAttcode(): string
	{
		return $this->attcode;
	}
	public function setAttcode(string $attcode): static
	{
		$this->attcode = $attcode;
		return $this;
	}

	public function getItemClass(): string
	{
		return $this->item_class;
	}
	public function setItemClass(string $item_class): static
	{
		$this->item_class = $item_class;
		return $this;
	}

	public function getItemId(): string
	{
		return $this->item_id;
	}
	public function setItemId(string $item_id): static
	{
		$this->item_id = $item_id;
		return $this;
	}

	public function getType(): CMDBChangeOpSetAttributeLinksAddRemoveType
	{
		return $this->type;
	}
	public function setType(CMDBChangeOpSetAttributeLinksAddRemoveType|string $type): static
	{
		if (is_string($type)) {
			$type = CMDBChangeOpSetAttributeLinksAddRemoveType::from($type);
		}
		$this->type = $type;
		return $this;
	}
}
