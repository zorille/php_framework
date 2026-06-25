<?php

namespace Zorille\itop\data_models;

class PDU extends FunctionalCI
{
    const ENTITY_NAME = 'PDU';

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
    protected ?string $macaddress = null;
    protected ?string $ipaddress_id = null;
    protected ?string $ipaddress_name = null;
    protected ?string $facilities_id = null;
    protected ?string $Armoires_list = null;
    protected ?string $powerstart_id = null;
    protected ?string $powerstart_name = null;
    protected ?string $rack_id = null;
    protected ?string $rack_name = null;
    protected ?string $enclosure_id = null;
    protected ?string $position_v = null;
    protected ?string $nb_u = null;
    protected ?string $position_p = null;
    protected ?string $zero_u = null;
    protected ?string $position_h = null;
    protected ?string $nb_cols = null;
    protected ?string $weight = null;
    protected ?string $boitiergtc_id = null;
    protected ?string $boitiergtc_name = null;
    protected ?string $oid_tdo = null;
    protected ?string $fqdn = null;
    protected ?string $rack_pod_name = null;
    protected ?string $rack_customer_name = null;
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $ipaddress_id_friendlyname = null;
    protected ?string $ipaddress_id_finalclass_recall = null;
    protected ?string $powerstart_id_friendlyname = null;
    protected ?string $powerstart_id_finalclass_recall = null;
    protected ?string $powerstart_id_obsolescence_flag = null;
    protected ?string $rack_id_friendlyname = null;
    protected ?string $rack_id_obsolescence_flag = null;
    protected ?string $enclosure_id_friendlyname = null;
    protected ?string $enclosure_id_obsolescence_flag = null;
    protected ?string $boitiergtc_id_friendlyname = null;
    protected ?string $boitiergtc_id_obsolescence_flag = null;
    protected string $finalclass = 'PDU';

    /**
     * @return string|null
     */
    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    /**
     * @param string|null $serialnumber
     * @return PDU
     */
    public function setSerialnumber(?string $serialnumber): PDU
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
     * @return PDU
     */
    public function setLocationId(?string $location_id): PDU
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
     * @return PDU
     */
    public function setLocationName(?string $location_name): PDU
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
     * @return PDU
     */
    public function setStatus(?string $status): PDU
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
     * @return PDU
     */
    public function setBrandId(?string $brand_id): PDU
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
     * @return PDU
     */
    public function setBrandName(?string $brand_name): PDU
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
     * @return PDU
     */
    public function setModelId(?string $model_id): PDU
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
     * @return PDU
     */
    public function setModelName(?string $model_name): PDU
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
     * @return PDU
     */
    public function setAssetNumber(?string $asset_number): PDU
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
     * @return PDU
     */
    public function setPurchaseDate(?string $purchase_date): PDU
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
     * @return PDU
     */
    public function setEndOfWarranty(?string $end_of_warranty): PDU
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
     * @return PDU
     */
    public function setMaintenanceDate(?string $maintenance_date): PDU
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
     * @return PDU
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): PDU
    {
        $this->maintenance_frequency = $maintenance_frequency;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMacaddress(): ?string
    {
        return $this->macaddress;
    }

    /**
     * @param string|null $macaddress
     * @return PDU
     */
    public function setMacaddress(?string $macaddress): PDU
    {
        $this->macaddress = $macaddress;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpaddressId(): ?string
    {
        return $this->ipaddress_id;
    }

    /**
     * @param string|null $ipaddress_id
     * @return PDU
     */
    public function setIpaddressId(?string $ipaddress_id): PDU
    {
        $this->ipaddress_id = $ipaddress_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpaddressName(): ?string
    {
        return $this->ipaddress_name;
    }

    /**
     * @param string|null $ipaddress_name
     * @return PDU
     */
    public function setIpaddressName(?string $ipaddress_name): PDU
    {
        $this->ipaddress_name = $ipaddress_name;
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
     * @return PDU
     */
    public function setFacilitiesId(?string $facilities_id): PDU
    {
        $this->facilities_id = $facilities_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArmoiresList(): ?string
    {
        return $this->Armoires_list;
    }

    /**
     * @param string|null $Armoires_list
     * @return PDU
     */
    public function setArmoiresList(?string $Armoires_list): PDU
    {
        $this->Armoires_list = $Armoires_list;
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
     * @return PDU
     */
    public function setPowerstartId(?string $powerstart_id): PDU
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
     * @return PDU
     */
    public function setPowerstartName(?string $powerstart_name): PDU
    {
        $this->powerstart_name = $powerstart_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackId(): ?string
    {
        return $this->rack_id;
    }

    /**
     * @param string|null $rack_id
     * @return PDU
     */
    public function setRackId(?string $rack_id): PDU
    {
        $this->rack_id = $rack_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackName(): ?string
    {
        return $this->rack_name;
    }

    /**
     * @param string|null $rack_name
     * @return PDU
     */
    public function setRackName(?string $rack_name): PDU
    {
        $this->rack_name = $rack_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosureId(): ?string
    {
        return $this->enclosure_id;
    }

    /**
     * @param string|null $enclosure_id
     * @return PDU
     */
    public function setEnclosureId(?string $enclosure_id): PDU
    {
        $this->enclosure_id = $enclosure_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPositionV(): ?string
    {
        return $this->position_v;
    }

    /**
     * @param string|null $position_v
     * @return PDU
     */
    public function setPositionV(?string $position_v): PDU
    {
        $this->position_v = $position_v;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNbU(): ?string
    {
        return $this->nb_u;
    }

    /**
     * @param string|null $nb_u
     * @return PDU
     */
    public function setNbU(?string $nb_u): PDU
    {
        $this->nb_u = $nb_u;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPositionP(): ?string
    {
        return $this->position_p;
    }

    /**
     * @param string|null $position_p
     * @return PDU
     */
    public function setPositionP(?string $position_p): PDU
    {
        $this->position_p = $position_p;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZeroU(): ?string
    {
        return $this->zero_u;
    }

    /**
     * @param string|null $zero_u
     * @return PDU
     */
    public function setZeroU(?string $zero_u): PDU
    {
        $this->zero_u = $zero_u;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPositionH(): ?string
    {
        return $this->position_h;
    }

    /**
     * @param string|null $position_h
     * @return PDU
     */
    public function setPositionH(?string $position_h): PDU
    {
        $this->position_h = $position_h;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNbCols(): ?string
    {
        return $this->nb_cols;
    }

    /**
     * @param string|null $nb_cols
     * @return PDU
     */
    public function setNbCols(?string $nb_cols): PDU
    {
        $this->nb_cols = $nb_cols;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * @param string|null $weight
     * @return PDU
     */
    public function setWeight(?string $weight): PDU
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoitiergtcId(): ?string
    {
        return $this->boitiergtc_id;
    }

    /**
     * @param string|null $boitiergtc_id
     * @return PDU
     */
    public function setBoitiergtcId(?string $boitiergtc_id): PDU
    {
        $this->boitiergtc_id = $boitiergtc_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoitiergtcName(): ?string
    {
        return $this->boitiergtc_name;
    }

    /**
     * @param string|null $boitiergtc_name
     * @return PDU
     */
    public function setBoitiergtcName(?string $boitiergtc_name): PDU
    {
        $this->boitiergtc_name = $boitiergtc_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOidTdo(): ?string
    {
        return $this->oid_tdo;
    }

    /**
     * @param string|null $oid_tdo
     * @return PDU
     */
    public function setOidTdo(?string $oid_tdo): PDU
    {
        $this->oid_tdo = $oid_tdo;
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
     * @return PDU
     */
    public function setFqdn(?string $fqdn): PDU
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackPodName(): ?string
    {
        return $this->rack_pod_name;
    }

    /**
     * @param string|null $rack_pod_name
     * @return PDU
     */
    public function setRackPodName(?string $rack_pod_name): PDU
    {
        $this->rack_pod_name = $rack_pod_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackCustomerName(): ?string
    {
        return $this->rack_customer_name;
    }

    /**
     * @param string|null $rack_customer_name
     * @return PDU
     */
    public function setRackCustomerName(?string $rack_customer_name): PDU
    {
        $this->rack_customer_name = $rack_customer_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    /**
     * @param string|null $friendlyname
     * @return PDU
     */
    public function setFriendlyname(?string $friendlyname): PDU
    {
        $this->friendlyname = $friendlyname;
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
     * @return PDU
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): PDU
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
     * @return PDU
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): PDU
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
     * @return PDU
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): PDU
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
     * @return PDU
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): PDU
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
     * @return PDU
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): PDU
    {
        $this->model_id_friendlyname = $model_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpaddressIdFriendlyname(): ?string
    {
        return $this->ipaddress_id_friendlyname;
    }

    /**
     * @param string|null $ipaddress_id_friendlyname
     * @return PDU
     */
    public function setIpaddressIdFriendlyname(?string $ipaddress_id_friendlyname): PDU
    {
        $this->ipaddress_id_friendlyname = $ipaddress_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpaddressIdFinalclassRecall(): ?string
    {
        return $this->ipaddress_id_finalclass_recall;
    }

    /**
     * @param string|null $ipaddress_id_finalclass_recall
     * @return PDU
     */
    public function setIpaddressIdFinalclassRecall(?string $ipaddress_id_finalclass_recall): PDU
    {
        $this->ipaddress_id_finalclass_recall = $ipaddress_id_finalclass_recall;
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
     * @return PDU
     */
    public function setPowerstartIdFriendlyname(?string $powerstart_id_friendlyname): PDU
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
     * @return PDU
     */
    public function setPowerstartIdFinalclassRecall(?string $powerstart_id_finalclass_recall): PDU
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
     * @return PDU
     */
    public function setPowerstartIdObsolescenceFlag(?string $powerstart_id_obsolescence_flag): PDU
    {
        $this->powerstart_id_obsolescence_flag = $powerstart_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackIdFriendlyname(): ?string
    {
        return $this->rack_id_friendlyname;
    }

    /**
     * @param string|null $rack_id_friendlyname
     * @return PDU
     */
    public function setRackIdFriendlyname(?string $rack_id_friendlyname): PDU
    {
        $this->rack_id_friendlyname = $rack_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRackIdObsolescenceFlag(): ?string
    {
        return $this->rack_id_obsolescence_flag;
    }

    /**
     * @param string|null $rack_id_obsolescence_flag
     * @return PDU
     */
    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): PDU
    {
        $this->rack_id_obsolescence_flag = $rack_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosureIdFriendlyname(): ?string
    {
        return $this->enclosure_id_friendlyname;
    }

    /**
     * @param string|null $enclosure_id_friendlyname
     * @return PDU
     */
    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): PDU
    {
        $this->enclosure_id_friendlyname = $enclosure_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosureIdObsolescenceFlag(): ?string
    {
        return $this->enclosure_id_obsolescence_flag;
    }

    /**
     * @param string|null $enclosure_id_obsolescence_flag
     * @return PDU
     */
    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): PDU
    {
        $this->enclosure_id_obsolescence_flag = $enclosure_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoitiergtcIdFriendlyname(): ?string
    {
        return $this->boitiergtc_id_friendlyname;
    }

    /**
     * @param string|null $boitiergtc_id_friendlyname
     * @return PDU
     */
    public function setBoitiergtcIdFriendlyname(?string $boitiergtc_id_friendlyname): PDU
    {
        $this->boitiergtc_id_friendlyname = $boitiergtc_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoitiergtcIdObsolescenceFlag(): ?string
    {
        return $this->boitiergtc_id_obsolescence_flag;
    }

    /**
     * @param string|null $boitiergtc_id_obsolescence_flag
     * @return PDU
     */
    public function setBoitiergtcIdObsolescenceFlag(?string $boitiergtc_id_obsolescence_flag): PDU
    {
        $this->boitiergtc_id_obsolescence_flag = $boitiergtc_id_obsolescence_flag;
        return $this;
    }
}