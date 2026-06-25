<?php

namespace Zorille\itop\data_models;

class BoitierGTC extends FunctionalCI
{
    const ENTITY_NAME = 'BoitierGTC';

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
    protected ?string $softwareinstance_id = null;
    protected ?string $softwareinstance_name = null;
    protected ?string $pdu_list = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $powerstart_id_friendlyname = null;
    protected ?string $powerstart_id_finalclass_recall = null;
    protected ?string $powerstart_id_obsolescence_flag = null;
    protected ?string $softwareinstance_id_friendlyname = null;
    protected ?string $softwareinstance_id_finalclass_recall = null;
    protected ?string $softwareinstance_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }
    /**
     * @param string|null $overview
     * @return BoitierGTC
     */
    public function setOverview(?string $overview): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setMonitoringList(?string $monitoring_list): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setSerialnumber(?string $serialnumber): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setLocationId(?string $location_id): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setLocationName(?string $location_name): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setStatus(?string $status): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setBrandId(?string $brand_id): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setBrandName(?string $brand_name): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setModelId(?string $model_id): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setModelName(?string $model_name): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setAssetNumber(?string $asset_number): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPurchaseDate(?string $purchase_date): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setEndOfWarranty(?string $end_of_warranty): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setMaintenanceDate(?string $maintenance_date): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPowerstartId(?string $powerstart_id): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPowerstartName(?string $powerstart_name): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setFqdn(?string $fqdn): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setFacilitiesId(?string $facilities_id): BoitierGTC
    {
        $this->facilities_id = $facilities_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareinstanceId(): ?string
    {
        return $this->softwareinstance_id;
    }
    /**
     * @param string|null $softwareinstance_id
     * @return BoitierGTC
     */
    public function setSoftwareinstanceId(?string $softwareinstance_id): BoitierGTC
    {
        $this->softwareinstance_id = $softwareinstance_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareinstanceName(): ?string
    {
        return $this->softwareinstance_name;
    }
    /**
     * @param string|null $softwareinstance_name
     * @return BoitierGTC
     */
    public function setSoftwareinstanceName(?string $softwareinstance_name): BoitierGTC
    {
        $this->softwareinstance_name = $softwareinstance_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPduList(): ?string
    {
        return $this->pdu_list;
    }
    /**
     * @param string|null $pdu_list
     * @return BoitierGTC
     */
    public function setPduList(?string $pdu_list): BoitierGTC
    {
        $this->pdu_list = $pdu_list;
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
     * @return BoitierGTC
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPowerstartIdFriendlyname(?string $powerstart_id_friendlyname): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPowerstartIdFinalclassRecall(?string $powerstart_id_finalclass_recall): BoitierGTC
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
     * @return BoitierGTC
     */
    public function setPowerstartIdObsolescenceFlag(?string $powerstart_id_obsolescence_flag): BoitierGTC
    {
        $this->powerstart_id_obsolescence_flag = $powerstart_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareinstanceIdFriendlyname(): ?string
    {
        return $this->softwareinstance_id_friendlyname;
    }
    /**
     * @param string|null $softwareinstance_id_friendlyname
     * @return BoitierGTC
     */
    public function setSoftwareinstanceIdFriendlyname(?string $softwareinstance_id_friendlyname): BoitierGTC
    {
        $this->softwareinstance_id_friendlyname = $softwareinstance_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareinstanceIdFinalclassRecall(): ?string
    {
        return $this->softwareinstance_id_finalclass_recall;
    }
    /**
     * @param string|null $softwareinstance_id_finalclass_recall
     * @return BoitierGTC
     */
    public function setSoftwareinstanceIdFinalclassRecall(?string $softwareinstance_id_finalclass_recall): BoitierGTC
    {
        $this->softwareinstance_id_finalclass_recall = $softwareinstance_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareinstanceIdObsolescenceFlag(): ?string
    {
        return $this->softwareinstance_id_obsolescence_flag;
    }
    /**
     * @param string|null $softwareinstance_id_obsolescence_flag
     * @return BoitierGTC
     */
    public function setSoftwareinstanceIdObsolescenceFlag(?string $softwareinstance_id_obsolescence_flag): BoitierGTC
    {
        $this->softwareinstance_id_obsolescence_flag = $softwareinstance_id_obsolescence_flag;
        return $this;
    }
}