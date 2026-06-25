<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class CMDBChangeOp extends data_model
{
	const ENTITY_NAME = 'CMDBChangeOp';

	protected int $id = -1;
	protected string $change = '';
	protected ?string $date = null;
	protected ?string $userinfo = null;
	protected ?string $user_id = null;
	protected string $objclass = '';
	protected string $objkey = '';
	protected string $finalclass = 'CMDBChangeOp';
	protected ?string $friendlyname = null;
	protected ?string $change_friendlyname = null;
	protected ?string $user_id_friendlyname = null;

	public function getId(): ?int
	{
		return $this->id;
	}
	public function setId(?int $id): static
	{
		$this->id = $id;
		return $this;
	}

	public function getChange(): string
	{
		return $this->change;
	}
	public function setChange(string $change): static
	{
		$this->change = $change;
		return $this;
	}

	public function getDate(): ?string
	{
		return $this->date;
	}
	public function setDate(?string $date): static
	{
		$this->date = $date;
		return $this;
	}

	public function getUserinfo(): ?string
	{
		return $this->userinfo;
	}
	public function setUserinfo(?string $userinfo): static
	{
		$this->userinfo = $userinfo;
		return $this;
	}

	public function getUserId(): ?string
	{
		return $this->user_id;
	}
	public function setUserId(?string $user_id): static
	{
		$this->user_id = $user_id;
		return $this;
	}

	public function getObjclass(): string
	{
		return $this->objclass;
	}
	public function setObjclass(string $objclass): static
	{
		$this->objclass = $objclass;
		return $this;
	}

	public function getObjkey(): string
	{
		return $this->objkey;
	}
	public function setObjkey(string $objkey): static
	{
		$this->objkey = $objkey;
		return $this;
	}

	public function getFinalclass(): string
	{
		return $this->finalclass;
	}
	public function setFinalclass(string $finalclass): static
	{
		$this->finalclass = $finalclass;
		return $this;
	}

	public function getFriendlyname(): ?string
	{
		return $this->friendlyname;
	}
	public function setFriendlyname(?string $friendlyname): static
	{
		$this->friendlyname = $friendlyname;
		return $this;
	}

	public function getChangeFriendlyname(): ?string
	{
		return $this->change_friendlyname;
	}
	public function setChangeFriendlyname(?string $change_friendlyname): static
	{
		$this->change_friendlyname = $change_friendlyname;
		return $this;
	}

	public function getUserIdFriendlyname(): ?string
	{
		return $this->user_id_friendlyname;
	}
	public function setUserIdFriendlyname(?string $user_id_friendlyname): static
	{
		$this->user_id_friendlyname = $user_id_friendlyname;
		return $this;
	}
}
