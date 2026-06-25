<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class ServiceFamily extends data_model
{
    const ENTITY_NAME = 'ServiceFamily';

    protected ?string $id = null;
    protected ?string $name = null;
    protected array $icon = [];
    protected array $services_list = [];
    protected ?string $friendlyname = null;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): ServiceFamily
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): ServiceFamily
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon(): array
    {
        return $this->icon;
    }
    public function setIcon(array $icon): ServiceFamily
    {
        $this->icon = $icon;
        return $this;
    }

    public function getServicesList(): array
    {
        return $this->services_list;
    }
    public function setServicesList(array $services_list): ServiceFamily
    {
        $this->services_list = $services_list;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(?string $friendlyname): ServiceFamily
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }
}