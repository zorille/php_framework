<?php

namespace Zorille\itop\data_models;

class PhysicalDevice extends FunctionalCI
{
    const ENTITY_NAME = 'PhysicalDevice';

    protected ?string $serialnumber = null;
    protected ?string $location_id = null;
    protected ?string $location_name = null;
    protected ?string $status = null;
    protected ?string $brand_id = null;
    protected ?string $brand_name = null;
    protected ?string $model_id = null;
    protected ?string $model_name = null;
    protected ?string $asset_number = null;
    protected ?string $purchase_date = null;
    protected ?string $end_of_warranty = null;
    protected ?string $maintenance_date = null;
    protected ?string $maintenance_frequency = null;
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected string $finalclass = 'PhysicalDevice';

    /**
     * @return string|null
     */
    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    /**
     * @param string|null $serialnumber
     * @return PhysicalDevice
     */
    public function setSerialnumber(?string $serialnumber): PhysicalDevice
    {
        $this->serialnumber = $serialnumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocationId(): ?string
    {
        return $this->location_id;
    }

    /**
     * @param string|null $location_id
     * @return PhysicalDevice
     */
    public function setLocationId(?string $location_id): PhysicalDevice
    {
        $this->location_id = $location_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocationName(): ?string
    {
        return $this->location_name;
    }

    /**
     * @param string|null $location_name
     * @return PhysicalDevice
     */
    public function setLocationName(?string $location_name): PhysicalDevice
    {
        $this->location_name = $location_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return PhysicalDevice
     */
    public function setStatus(?string $status): PhysicalDevice
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrandId(): ?string
    {
        return $this->brand_id;
    }

    /**
     * @param string|null $brand_id
     * @return PhysicalDevice
     */
    public function setBrandId(?string $brand_id): PhysicalDevice
    {
        $this->brand_id = $brand_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    /**
     * @param string|null $brand_name
     * @return PhysicalDevice
     */
    public function setBrandName(?string $brand_name): PhysicalDevice
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModelId(): ?string
    {
        return $this->model_id;
    }

    /**
     * @param string|null $model_id
     * @return PhysicalDevice
     */
    public function setModelId(?string $model_id): PhysicalDevice
    {
        $this->model_id = $model_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModelName(): ?string
    {
        return $this->model_name;
    }

    /**
     * @param string|null $model_name
     * @return PhysicalDevice
     */
    public function setModelName(?string $model_name): PhysicalDevice
    {
        $this->model_name = $model_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAssetNumber(): ?string
    {
        return $this->asset_number;
    }

    /**
     * @param string|null $asset_number
     * @return PhysicalDevice
     */
    public function setAssetNumber(?string $asset_number): PhysicalDevice
    {
        $this->asset_number = $asset_number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPurchaseDate(): ?string
    {
        return $this->purchase_date;
    }

    /**
     * @param string|null $purchase_date
     * @return PhysicalDevice
     */
    public function setPurchaseDate(?string $purchase_date): PhysicalDevice
    {
        $this->purchase_date = $purchase_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndOfWarranty(): ?string
    {
        return $this->end_of_warranty;
    }

    /**
     * @param string|null $end_of_warranty
     * @return PhysicalDevice
     */
    public function setEndOfWarranty(?string $end_of_warranty): PhysicalDevice
    {
        $this->end_of_warranty = $end_of_warranty;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaintenanceDate(): ?string
    {
        return $this->maintenance_date;
    }

    /**
     * @param string|null $maintenance_date
     * @return PhysicalDevice
     */
    public function setMaintenanceDate(?string $maintenance_date): PhysicalDevice
    {
        $this->maintenance_date = $maintenance_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaintenanceFrequency(): ?string
    {
        return $this->maintenance_frequency;
    }

    /**
     * @param string|null $maintenance_frequency
     * @return PhysicalDevice
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): PhysicalDevice
    {
        $this->maintenance_frequency = $maintenance_frequency;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }

    /**
     * @param string|null $obsolescence_flag
     * @return PhysicalDevice
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): PhysicalDevice
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocationIdFriendlyname(): ?string
    {
        return $this->location_id_friendlyname;
    }

    /**
     * @param string|null $location_id_friendlyname
     * @return PhysicalDevice
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): PhysicalDevice
    {
        $this->location_id_friendlyname = $location_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocationIdObsolescenceFlag(): ?string
    {
        return $this->location_id_obsolescence_flag;
    }

    /**
     * @param string|null $location_id_obsolescence_flag
     * @return PhysicalDevice
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): PhysicalDevice
    {
        $this->location_id_obsolescence_flag = $location_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrandIdFriendlyname(): ?string
    {
        return $this->brand_id_friendlyname;
    }

    /**
     * @param string|null $brand_id_friendlyname
     * @return PhysicalDevice
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): PhysicalDevice
    {
        $this->brand_id_friendlyname = $brand_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModelIdFriendlyname(): ?string
    {
        return $this->model_id_friendlyname;
    }

    /**
     * @param string|null $model_id_friendlyname
     * @return PhysicalDevice
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): PhysicalDevice
    {
        $this->model_id_friendlyname = $model_id_friendlyname;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    /**
     * @param string $finalclass
     * @return PhysicalDevice
     */
    public function setFinalclass(string $finalclass): PhysicalDevice
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}