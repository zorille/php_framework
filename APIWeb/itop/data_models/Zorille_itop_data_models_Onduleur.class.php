<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class Onduleur extends FunctionalCI
{
    protected string|null $serialnumber = null;
    protected string|null $location_id = null;
    protected string|null $location_name = null;
    protected string|null $status = null;
    protected string|null $brand_id = null;
    protected string|null $brand_name = null;
    protected string|null $model_id = null;
    protected string|null $model_name = null;
    protected string|null $asset_number = null;
    protected string|null $purchase_date = null;
    protected string|null $end_of_warranty = null;
    protected string|null $maintenance_date = null;
    protected string|null $maintenance_frequency = null;
    protected string|null $macaddress = null;
    protected string|null $ipaddress_id = null;
    protected string|null $ipaddress_name = null;
    protected string|null $facilities_id = null;
    protected string|null $Armoires_list = null;
    protected string|null $powerstart_id = null;
    protected string|null $powerstart_name = null;
    protected string|null $fqdn = null;
    protected string|null $batteries_list = null;
    protected string $finalclass = 'Onduleur';

    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    public function setSerialnumber(?string $serialnumber): Onduleur
    {
        $this->serialnumber = $serialnumber;
        return $this;
    }

    public function getLocationId(): ?string
    {
        return $this->location_id;
    }

    public function setLocationId(?string $location_id): Onduleur
    {
        $this->location_id = $location_id;
        return $this;
    }

    public function getLocationName(): ?string
    {
        return $this->location_name;
    }

    public function setLocationName(?string $location_name): Onduleur
    {
        $this->location_name = $location_name;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): Onduleur
    {
        $this->status = $status;
        return $this;
    }

    public function getBrandId(): ?string
    {
        return $this->brand_id;
    }

    public function setBrandId(?string $brand_id): Onduleur
    {
        $this->brand_id = $brand_id;
        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(?string $brand_name): Onduleur
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    public function getModelId(): ?string
    {
        return $this->model_id;
    }

    public function setModelId(?string $model_id): Onduleur
    {
        $this->model_id = $model_id;
        return $this;
    }

    public function getModelName(): ?string
    {
        return $this->model_name;
    }

    public function setModelName(?string $model_name): Onduleur
    {
        $this->model_name = $model_name;
        return $this;
    }

    public function getAssetNumber(): ?string
    {
        return $this->asset_number;
    }

    public function setAssetNumber(?string $asset_number): Onduleur
    {
        $this->asset_number = $asset_number;
        return $this;
    }

    public function getPurchaseDate(): ?string
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(?string $purchase_date): Onduleur
    {
        $this->purchase_date = $purchase_date;
        return $this;
    }

    public function getEndOfWarranty(): ?string
    {
        return $this->end_of_warranty;
    }

    public function setEndOfWarranty(?string $end_of_warranty): Onduleur
    {
        $this->end_of_warranty = $end_of_warranty;
        return $this;
    }

    public function getMaintenanceDate(): ?string
    {
        return $this->maintenance_date;
    }

    public function setMaintenanceDate(?string $maintenance_date): Onduleur
    {
        $this->maintenance_date = $maintenance_date;
        return $this;
    }

    public function getMaintenanceFrequency(): ?string
    {
        return $this->maintenance_frequency;
    }

    public function setMaintenanceFrequency(?string $maintenance_frequency): Onduleur
    {
        $this->maintenance_frequency = $maintenance_frequency;
        return $this;
    }

    public function getMacaddress(): ?string
    {
        return $this->macaddress;
    }

    public function setMacaddress(?string $macaddress): Onduleur
    {
        $this->macaddress = $macaddress;
        return $this;
    }

    public function getIpaddressId(): ?string
    {
        return $this->ipaddress_id;
    }

    public function setIpaddressId(?string $ipaddress_id): Onduleur
    {
        $this->ipaddress_id = $ipaddress_id;
        return $this;
    }

    public function getIpaddressName(): ?string
    {
        return $this->ipaddress_name;
    }

    public function setIpaddressName(?string $ipaddress_name): Onduleur
    {
        $this->ipaddress_name = $ipaddress_name;
        return $this;
    }

    public function getFacilitiesId(): ?string
    {
        return $this->facilities_id;
    }

    public function setFacilitiesId(?string $facilities_id): Onduleur
    {
        $this->facilities_id = $facilities_id;
        return $this;
    }

    public function getArmoiresList(): ?string
    {
        return $this->Armoires_list;
    }

    public function setArmoiresList(?string $Armoires_list): Onduleur
    {
        $this->Armoires_list = $Armoires_list;
        return $this;
    }

    public function getPowerstartId(): ?string
    {
        return $this->powerstart_id;
    }

    public function setPowerstartId(?string $powerstart_id): Onduleur
    {
        $this->powerstart_id = $powerstart_id;
        return $this;
    }

    public function getPowerstartName(): ?string
    {
        return $this->powerstart_name;
    }

    public function setPowerstartName(?string $powerstart_name): Onduleur
    {
        $this->powerstart_name = $powerstart_name;
        return $this;
    }

    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    public function setFqdn(?string $fqdn): Onduleur
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    public function getBatteriesList(): ?string
    {
        return $this->batteries_list;
    }

    public function setBatteriesList(?string $batteries_list): Onduleur
    {
        $this->batteries_list = $batteries_list;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): Onduleur
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}