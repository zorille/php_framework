<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class Person extends data_model
{
    const ENTITY_NAME = 'Person';

    protected ?int $id = null;
    protected ?string $name = null;
    protected ?string $friendlyname = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id = null;
    protected ?string $email = null;
    protected ?string $employee_number = null;
    protected ?string $status = null;
    protected ?string $obsolescence_date = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $class = null;
    protected array $team_list = [];
    protected array $cis_list = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getFriendlyname(): string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(string $friendlyname): self
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getOrgIdFriendlyname(): string
    {
        return $this->org_id_friendlyname;
    }
    public function setOrgIdFriendlyname(string $org_id_friendlyname): self
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgId(): string
    {
        return $this->org_id;
    }
    public function setOrgId(string $org_id): self
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getEmail(): string
    {
        return strtolower($this->email);
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmployeeNumber(): string
    {
        return $this->employee_number;
    }
    public function setEmployeeNumber(string $employee_number): self
    {
        $this->employee_number = $employee_number;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getObsolescenceDate(): string
    {
        return $this->obsolescence_date;
    }
    public function setObsolescenceDate(string $obsolescence_date): self
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    public function getObsolescenceFlag(): string
    {
        return $this->obsolescence_flag;
    }
    public function setObsolescenceFlag(string $obsolescence_flag): self
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }
    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function getTeamList(): array
    {
        return $this->team_list;
    }
    public function setTeamList(array $team_list): self
    {
        $this->team_list = $team_list;
        return $this;
    }

    public function getCisList(): array
    {
        return $this->cis_list;
    }
    public function setCisList(array $cis_list): self
    {
        $this->cis_list = $cis_list;
        return $this;
    }

    public function getCodeClient(): string
    {
        preg_match_all(
            '/^(?<code_client>[0-9S]+)/m',
            $this->getOrgIdFriendlyName(),
            $matches,
            PREG_SET_ORDER
        );

        if (empty($matches[0])) {
            return "";
        }

        return $matches[0]['code_client'];
    }
}