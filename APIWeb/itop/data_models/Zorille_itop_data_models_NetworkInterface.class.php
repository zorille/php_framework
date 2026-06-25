<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class NetworkInterface extends data_model {
    public ?string $id = null;
    public ?string $name = null;
    public ?string $operational_status = null;
    public ?string $org_id = null;
    public ?string $organization_name = null;
    public ?string $speed = null;
    public ?string $topology = null;
    public ?string $wwn = null;
    public ?string $datacenterdevice_id = null;
    public ?string $datacenterdevice_name = null;
    public ?string $location_id = null;
    public ?string $status = null;
    public ?string $friendlyname = null;
    public ?string $obsolescence_flag = null;
    public ?string $obsolescence_date = null;
    public ?string $org_id_friendlyname = null;
    public ?string $org_id_obsolescence_flag = null;
    public ?string $datacenterdevice_id_friendlyname = null;
    public ?string $datacenterdevice_id_finalclass_recall = null;
    public ?string $datacenterdevice_id_obsolescence_flag = null;
    public ?string $location_id_friendlyname = null;
    public ?string $location_id_obsolescence_flag = null;

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

    public function getOperationalStatus(): ?string
    {
        return $this->operational_status;
    }

    public function setOperationalStatus(?string $operational_status): static
    {
        $this->operational_status = $operational_status;
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

    public function getOrganizationName(): ?string
    {
        return $this->organization_name;
    }

    public function setOrganizationName(?string $organization_name): static
    {
        $this->organization_name = $organization_name;
        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(?string $speed): static
    {
        $this->speed = $speed;
        return $this;
    }

    public function getTopology(): ?string
    {
        return $this->topology;
    }

    public function setTopology(?string $topology): static
    {
        $this->topology = $topology;
        return $this;
    }

    public function getWwn(): ?string
    {
        return $this->wwn;
    }

    public function setWwn(?string $wwn): static
    {
        $this->wwn = $wwn;
        return $this;
    }

    public function getDatacenterdeviceId(): ?string
    {
        return $this->datacenterdevice_id;
    }

    public function setDatacenterdeviceId(?string $datacenterdevice_id): static
    {
        $this->datacenterdevice_id = $datacenterdevice_id;
        return $this;
    }

    public function getDatacenterdeviceName(): ?string
    {
        return $this->datacenterdevice_name;
    }

    public function setDatacenterdeviceName(?string $datacenterdevice_name): static
    {
        $this->datacenterdevice_name = $datacenterdevice_name;
        return $this;
    }

    public function getLocationId(): ?string
    {
        return $this->location_id;
    }

    public function setLocationId(?string $location_id): static
    {
        $this->location_id = $location_id;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
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

    public function getDatacenterdeviceIdFriendlyname(): ?string
    {
        return $this->datacenterdevice_id_friendlyname;
    }

    public function setDatacenterdeviceIdFriendlyname(?string $datacenterdevice_id_friendlyname): static
    {
        $this->datacenterdevice_id_friendlyname = $datacenterdevice_id_friendlyname;
        return $this;
    }

    public function getDatacenterdeviceIdFinalclassRecall(): ?string
    {
        return $this->datacenterdevice_id_finalclass_recall;
    }

    public function setDatacenterdeviceIdFinalclassRecall(?string $datacenterdevice_id_finalclass_recall): static
    {
        $this->datacenterdevice_id_finalclass_recall = $datacenterdevice_id_finalclass_recall;
        return $this;
    }

    public function getDatacenterdeviceIdObsolescenceFlag(): ?string
    {
        return $this->datacenterdevice_id_obsolescence_flag;
    }

    public function setDatacenterdeviceIdObsolescenceFlag(?string $datacenterdevice_id_obsolescence_flag): static
    {
        $this->datacenterdevice_id_obsolescence_flag = $datacenterdevice_id_obsolescence_flag;
        return $this;
    }

    public function getLocationIdFriendlyname(): ?string
    {
        return $this->location_id_friendlyname;
    }

    public function setLocationIdFriendlyname(?string $location_id_friendlyname): static
    {
        $this->location_id_friendlyname = $location_id_friendlyname;
        return $this;
    }

    public function getLocationIdObsolescenceFlag(): ?string
    {
        return $this->location_id_obsolescence_flag;
    }

    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): static
    {
        $this->location_id_obsolescence_flag = $location_id_obsolescence_flag;
        return $this;
    }
    
    public string $finalclass = 'FiberChannelInterface';
}