<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class Location extends data_model
{
    const ENTITY_NAME = 'Location';

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?StatusEnum $status = StatusEnum::ACTIVE;
    protected ?string $org_id = null;
    protected ?string $org_name = null;
    protected ?string $postal_code = null;
    protected ?string $city = null;
    protected ?string $country = null;
    protected array $person_list = [];
    protected array $physicaldevice_list = [];
    protected ?int $locationtype_id = null;
    protected ?int $parent_id = null;
    protected array $locations_list = [];
    protected array $accesspermissions_list = [];
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $obsolescence_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $locationtype_id_friendlyname = null;
    protected ?string $parent_id_friendlyname = null;
    protected ?string $parent_id_obsolescence_flag = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }

    public function setStatus(?StatusEnum $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getOrgId(): ?string
    {
        return $this->org_id;
    }

    public function setOrgId(?string $org_id): static
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrgName(): ?string
    {
        return $this->org_name;
    }

    public function setOrgName(?string $org_name): static
    {
        $this->org_name = $org_name;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): static
    {
        $this->postal_code = $postal_code;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getPersonList(): array
    {
        return $this->person_list;
    }

    public function setPersonList(array $person_list): static
    {
        $this->person_list = $person_list;
        return $this;
    }

    public function getPhysicaldeviceList(): array
    {
        return $this->physicaldevice_list;
    }

    public function setPhysicaldeviceList(array $physicaldevice_list): static
    {
        $this->physicaldevice_list = $physicaldevice_list;
        return $this;
    }

    public function getLocationtypeId(): ?int
    {
        return $this->locationtype_id;
    }

    public function setLocationtypeId(?int $locationtype_id): static
    {
        $this->locationtype_id = $locationtype_id;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): static
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    public function getLocationsList(): array
    {
        return $this->locations_list;
    }

    public function setLocationsList(array $locations_list): static
    {
        $this->locations_list = $locations_list;
        return $this;
    }

    public function getAccesspermissionsList(): array
    {
        return $this->accesspermissions_list;
    }

    public function setAccesspermissionsList(array $accesspermissions_list): static
    {
        $this->accesspermissions_list = $accesspermissions_list;
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

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }

    public function setObsolescenceFlag(?string $obsolescence_flag): static
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getObsolescenceDate(): ?string
    {
        return $this->obsolescence_date;
    }

    public function setObsolescenceDate(?string $obsolescence_date): static
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }

    public function setOrgIdFriendlyname(?string $org_id_friendlyname): static
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }

    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): static
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }

    public function getLocationtypeIdFriendlyname(): ?string
    {
        return $this->locationtype_id_friendlyname;
    }

    public function setLocationtypeIdFriendlyname(?string $locationtype_id_friendlyname): static
    {
        $this->locationtype_id_friendlyname = $locationtype_id_friendlyname;
        return $this;
    }

    public function getParentIdFriendlyname(): ?string
    {
        return $this->parent_id_friendlyname;
    }

    public function setParentIdFriendlyname(?string $parent_id_friendlyname): static
    {
        $this->parent_id_friendlyname = $parent_id_friendlyname;
        return $this;
    }

    public function getParentIdObsolescenceFlag(): ?string
    {
        return $this->parent_id_obsolescence_flag;
    }

    public function setParentIdObsolescenceFlag(?string $parent_id_obsolescence_flag): static
    {
        $this->parent_id_obsolescence_flag = $parent_id_obsolescence_flag;
        return $this;
    }
}