<?php

namespace Zorille\itop\data_models;

class CentraleAlarme extends FunctionalCI
{
    const ENTITY_NAME = 'CentraleAlarme';

    protected ?string $overview = null;
    protected ?string $monitoring_list = null;
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
    protected ?string $powerstart_id = null;
    protected ?string $powerstart_name = null;
    protected ?string $fqdn = null;
    protected ?string $facilities_id = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $powerstart_id_friendlyname = null;
    protected ?string $powerstart_id_finalclass_recall = null;
    protected ?string $powerstart_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }
    /**
     * @param string|null $overview
     * @return CentraleAlarme
     */
    public function setOverview(?string $overview): CentraleAlarme
    {
        $this->overview = $overview;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringList(): ?string
    {
        return $this->monitoring_list;
    }
    /**
     * @param string|null $monitoring_list
     * @return CentraleAlarme
     */
    public function setMonitoringList(?string $monitoring_list): CentraleAlarme
    {
        $this->monitoring_list = $monitoring_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }
    /**
     * @param string|null $serialnumber
     * @return CentraleAlarme
     */
    public function setSerialnumber(?string $serialnumber): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setLocationId(?string $location_id): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setLocationName(?string $location_name): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setStatus(?string $status): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setBrandId(?string $brand_id): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setBrandName(?string $brand_name): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setModelId(?string $model_id): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setModelName(?string $model_name): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setAssetNumber(?string $asset_number): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setPurchaseDate(?string $purchase_date): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setEndOfWarranty(?string $end_of_warranty): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setMaintenanceDate(?string $maintenance_date): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): CentraleAlarme
    {
        $this->maintenance_frequency = $maintenance_frequency;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstartId(): ?string
    {
        return $this->powerstart_id;
    }
    /**
     * @param string|null $powerstart_id
     * @return CentraleAlarme
     */
    public function setPowerstartId(?string $powerstart_id): CentraleAlarme
    {
        $this->powerstart_id = $powerstart_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstartName(): ?string
    {
        return $this->powerstart_name;
    }
    /**
     * @param string|null $powerstart_name
     * @return CentraleAlarme
     */
    public function setPowerstartName(?string $powerstart_name): CentraleAlarme
    {
        $this->powerstart_name = $powerstart_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }
    /**
     * @param string|null $fqdn
     * @return CentraleAlarme
     */
    public function setFqdn(?string $fqdn): CentraleAlarme
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFacilitiesId(): ?string
    {
        return $this->facilities_id;
    }
    /**
     * @param string|null $facilities_id
     * @return CentraleAlarme
     */
    public function setFacilitiesId(?string $facilities_id): CentraleAlarme
    {
        $this->facilities_id = $facilities_id;
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
     * @return CentraleAlarme
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): CentraleAlarme
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
     * @return CentraleAlarme
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): CentraleAlarme
    {
        $this->model_id_friendlyname = $model_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstartIdFriendlyname(): ?string
    {
        return $this->powerstart_id_friendlyname;
    }
    /**
     * @param string|null $powerstart_id_friendlyname
     * @return CentraleAlarme
     */
    public function setPowerstartIdFriendlyname(?string $powerstart_id_friendlyname): CentraleAlarme
    {
        $this->powerstart_id_friendlyname = $powerstart_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstartIdFinalclassRecall(): ?string
    {
        return $this->powerstart_id_finalclass_recall;
    }
    /**
     * @param string|null $powerstart_id_finalclass_recall
     * @return CentraleAlarme
     */
    public function setPowerstartIdFinalclassRecall(?string $powerstart_id_finalclass_recall): CentraleAlarme
    {
        $this->powerstart_id_finalclass_recall = $powerstart_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstartIdObsolescenceFlag(): ?string
    {
        return $this->powerstart_id_obsolescence_flag;
    }
    /**
     * @param string|null $powerstart_id_obsolescence_flag
     * @return CentraleAlarme
     */
    public function setPowerstartIdObsolescenceFlag(?string $powerstart_id_obsolescence_flag): CentraleAlarme
    {
        $this->powerstart_id_obsolescence_flag = $powerstart_id_obsolescence_flag;
        return $this;
    }
}