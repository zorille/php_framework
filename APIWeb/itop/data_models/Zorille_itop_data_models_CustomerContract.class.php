<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class CustomerContract extends data_model
{
    const ENTITY_NAME = 'CustomerContract';

    private ?int $id = null;
    private ?string $name = null;
    private ?string $friendlyname = null;
    private ?string $org_id = null;
    private ?string $organization_name = null;
    private ?string $description = null;
    private ?string $org_id_friendlyname = null;
    private ?string $provider_id_friendlyname = null;
    private ?string $provider_id = null;
    private ?string $start_date = null;

    private ?string $status = null;
    private array $functionalcis_list = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(?string $friendlyname): self
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getOrgId(): ?string
    {
        return $this->org_id;
    }
    public function setOrgId(?string $org_id): self
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrganizationName(): ?string
    {
        return $this->organization_name;
    }
    public function setOrganizationName(?string $organization_name): CustomerContract
    {
        $this->organization_name = $organization_name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): CustomerContract
    {
        $this->description = $description;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): CustomerContract
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getProviderIdFriendlyname(): ?string
    {
        return $this->provider_id_friendlyname;
    }
    public function setProviderIdFriendlyname(?string $provider_id_friendlyname): CustomerContract
    {
        $this->provider_id_friendlyname = $provider_id_friendlyname;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getFunctionalcisList(): array
    {
        return $this->functionalcis_list;
    }
    public function setFunctionalcisList(array $functionalcis_list): self
    {
        $this->functionalcis_list = $functionalcis_list;
        return $this;
    }

    public function getProviderId(): ?string
    {
        return $this->provider_id;
    }
    public function setProviderId(?string $provider_id): CustomerContract
    {
        $this->provider_id = $provider_id;
        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }
    public function setStartDate(?string $start_date): CustomerContract
    {
        $this->start_date = $start_date;
        return $this;
    }
}