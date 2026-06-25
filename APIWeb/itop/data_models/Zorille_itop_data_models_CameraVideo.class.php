<?php

namespace Zorille\itop\data_models;

class CameraVideo extends FunctionalCI
{
    const ENTITY_NAME = 'CameraVideo';

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
    protected ?string $enregistreurcctv_id = null;
    protected ?string $enregistreurcctv_name = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $powerstart_id_friendlyname = null;
    protected ?string $powerstart_id_finalclass_recall = null;
    protected ?string $powerstart_id_obsolescence_flag = null;
    protected ?string $enregistreurcctv_id_friendlyname = null;
    protected ?string $enregistreurcctv_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }
    /**
     * @param string|null $overview
     * @return CameraVideo
     */
    public function setOverview(?string $overview): CameraVideo
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
     * @return CameraVideo
     */
    public function setMonitoringList(?string $monitoring_list): CameraVideo
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
     * @return CameraVideo
     */
    public function setSerialnumber(?string $serialnumber): CameraVideo
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
     * @return CameraVideo
     */
    public function setLocationId(?string $location_id): CameraVideo
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
     * @return CameraVideo
     */
    public function setLocationName(?string $location_name): CameraVideo
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
     * @return CameraVideo
     */
    public function setStatus(?string $status): CameraVideo
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
     * @return CameraVideo
     */
    public function setBrandId(?string $brand_id): CameraVideo
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
     * @return CameraVideo
     */
    public function setBrandName(?string $brand_name): CameraVideo
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
     * @return CameraVideo
     */
    public function setModelId(?string $model_id): CameraVideo
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
     * @return CameraVideo
     */
    public function setModelName(?string $model_name): CameraVideo
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
     * @return CameraVideo
     */
    public function setAssetNumber(?string $asset_number): CameraVideo
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
     * @return CameraVideo
     */
    public function setPurchaseDate(?string $purchase_date): CameraVideo
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
     * @return CameraVideo
     */
    public function setEndOfWarranty(?string $end_of_warranty): CameraVideo
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
     * @return CameraVideo
     */
    public function setMaintenanceDate(?string $maintenance_date): CameraVideo
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
     * @return CameraVideo
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): CameraVideo
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
     * @return CameraVideo
     */
    public function setPowerstartId(?string $powerstart_id): CameraVideo
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
     * @return CameraVideo
     */
    public function setPowerstartName(?string $powerstart_name): CameraVideo
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
     * @return CameraVideo
     */
    public function setFqdn(?string $fqdn): CameraVideo
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
     * @return CameraVideo
     */
    public function setFacilitiesId(?string $facilities_id): CameraVideo
    {
        $this->facilities_id = $facilities_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnregistreurcctvId(): ?string
    {
        return $this->enregistreurcctv_id;
    }
    /**
     * @param string|null $enregistreurcctv_id
     * @return CameraVideo
     */
    public function setEnregistreurcctvId(?string $enregistreurcctv_id): CameraVideo
    {
        $this->enregistreurcctv_id = $enregistreurcctv_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnregistreurcctvName(): ?string
    {
        return $this->enregistreurcctv_name;
    }
    /**
     * @param string|null $enregistreurcctv_name
     * @return CameraVideo
     */
    public function setEnregistreurcctvName(?string $enregistreurcctv_name): CameraVideo
    {
        $this->enregistreurcctv_name = $enregistreurcctv_name;
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
     * @return CameraVideo
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): CameraVideo
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
     * @return CameraVideo
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): CameraVideo
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
     * @return CameraVideo
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): CameraVideo
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
     * @return CameraVideo
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): CameraVideo
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
     * @return CameraVideo
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): CameraVideo
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
     * @return CameraVideo
     */
    public function setPowerstartIdFriendlyname(?string $powerstart_id_friendlyname): CameraVideo
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
     * @return CameraVideo
     */
    public function setPowerstartIdFinalclassRecall(?string $powerstart_id_finalclass_recall): CameraVideo
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
     * @return CameraVideo
     */
    public function setPowerstartIdObsolescenceFlag(?string $powerstart_id_obsolescence_flag): CameraVideo
    {
        $this->powerstart_id_obsolescence_flag = $powerstart_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnregistreurcctvIdFriendlyname(): ?string
    {
        return $this->enregistreurcctv_id_friendlyname;
    }
    /**
     * @param string|null $enregistreurcctv_id_friendlyname
     * @return CameraVideo
     */
    public function setEnregistreurcctvIdFriendlyname(?string $enregistreurcctv_id_friendlyname): CameraVideo
    {
        $this->enregistreurcctv_id_friendlyname = $enregistreurcctv_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnregistreurcctvIdObsolescenceFlag(): ?string
    {
        return $this->enregistreurcctv_id_obsolescence_flag;
    }
    /**
     * @param string|null $enregistreurcctv_id_obsolescence_flag
     * @return CameraVideo
     */
    public function setEnregistreurcctvIdObsolescenceFlag(?string $enregistreurcctv_id_obsolescence_flag): CameraVideo
    {
        $this->enregistreurcctv_id_obsolescence_flag = $enregistreurcctv_id_obsolescence_flag;
        return $this;
    }
}