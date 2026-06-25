<?php

namespace Zorille\itop\data_models;

class Wifi extends FunctionalCI
{
    const ENTITY_NAME = 'Wifi';

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
    protected ?string $networkdevice_list = null;
    protected ?string $physicalinterface_list = null;
    protected ?string $rack_id = null;
    protected ?string $rack_name = null;
    protected ?string $enclosure_id = null;
    protected ?string $enclosure_name = null;
    protected ?string $nb_u = null;
    protected ?string $powerA_id = null;
    protected ?string $powerA_name = null;
    protected ?string $powerB_id = null;
    protected ?string $powerB_name = null;
    protected ?string $fiberinterfacelist_list = null;
    protected ?string $san_list = null;
    protected ?string $redundancy = null;
    protected ?string $position_v = null;
    protected ?string $position_p = null;
    protected ?string $zero_u = null;
    protected ?string $position_h = null;
    protected ?string $nb_cols = null;
    protected ?string $weight = null;
    protected ?string $expected_power_input = null;
    protected ?string $managementip_id = null;
    protected ?string $managementip_name = null;
    protected ?string $networkA_id = null;
    protected ?string $networkA_name = null;
    protected ?string $networkB_id = null;
    protected ?string $networkB_name = null;
    protected ?string $redundancy_network = null;
    protected ?string $networkdevicetype_id = null;
    protected ?string $networkdevicetype_name = null;
    protected ?string $connectablecis_list = null;
    protected ?string $iosversion_id = null;
    protected ?string $iosversion_name = null;
    protected ?string $ram = null;
    protected ?string $networkdevicevirtualinterfaces_list = null;
    protected ?string $fqdn = null;
    protected ?string $test = null;
    protected string $finalclass = 'Wifi';
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $rack_id_friendlyname = null;
    protected ?string $rack_id_obsolescence_flag = null;
    protected ?string $enclosure_id_friendlyname = null;
    protected ?string $enclosure_id_obsolescence_flag = null;
    protected ?string $powerA_id_friendlyname = null;
    protected ?string $powerA_id_finalclass_recall = null;
    protected ?string $powerA_id_obsolescence_flag = null;
    protected ?string $powerB_id_friendlyname = null;
    protected ?string $powerB_id_finalclass_recall = null;
    protected ?string $powerB_id_obsolescence_flag = null;
    protected ?string $managementip_id_friendlyname = null;
    protected ?string $managementip_id_finalclass_recall = null;
    protected ?string $networkA_id_friendlyname = null;
    protected ?string $networkA_id_obsolescence_flag = null;
    protected ?string $networkB_id_friendlyname = null;
    protected ?string $networkB_id_obsolescence_flag = null;
    protected ?string $networkdevicetype_id_friendlyname = null;
    protected ?string $iosversion_id_friendlyname = null;

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }

    /**
     * @param string|null $overview
     * @return Wifi
     */
    public function setOverview(?string $overview): Wifi
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
     * @return Wifi
     */
    public function setMonitoringList(?string $monitoring_list): Wifi
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
     * @return Wifi
     */
    public function setSerialnumber(?string $serialnumber): Wifi
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
     * @return Wifi
     */
    public function setLocationId(?string $location_id): Wifi
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
     * @return Wifi
     */
    public function setLocationName(?string $location_name): Wifi
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
     * @return Wifi
     */
    public function setStatus(?string $status): Wifi
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
     * @return Wifi
     */
    public function setBrandId(?string $brand_id): Wifi
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
     * @return Wifi
     */
    public function setBrandName(?string $brand_name): Wifi
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
     * @return Wifi
     */
    public function setModelId(?string $model_id): Wifi
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
     * @return Wifi
     */
    public function setModelName(?string $model_name): Wifi
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
     * @return Wifi
     */
    public function setAssetNumber(?string $asset_number): Wifi
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
     * @return Wifi
     */
    public function setPurchaseDate(?string $purchase_date): Wifi
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
     * @return Wifi
     */
    public function setEndOfWarranty(?string $end_of_warranty): Wifi
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
     * @return Wifi
     */
    public function setMaintenanceDate(?string $maintenance_date): Wifi
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
     * @return Wifi
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): Wifi
    {
        $this->maintenance_frequency = $maintenance_frequency;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkdeviceList(): ?string
    {
        return $this->networkdevice_list;
    }

    /**
     * @param string|null $networkdevice_list
     * @return Wifi
     */
    public function setNetworkdeviceList(?string $networkdevice_list): Wifi
    {
        $this->networkdevice_list = $networkdevice_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhysicalinterfaceList(): ?string
    {
        return $this->physicalinterface_list;
    }

    /**
     * @param string|null $physicalinterface_list
     * @return Wifi
     */
    public function setPhysicalinterfaceList(?string $physicalinterface_list): Wifi
    {
        $this->physicalinterface_list = $physicalinterface_list;
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
     * @return Wifi
     */
    public function setRackId(?string $rack_id): Wifi
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
     * @return Wifi
     */
    public function setRackName(?string $rack_name): Wifi
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
     * @return Wifi
     */
    public function setEnclosureId(?string $enclosure_id): Wifi
    {
        $this->enclosure_id = $enclosure_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosureName(): ?string
    {
        return $this->enclosure_name;
    }

    /**
     * @param string|null $enclosure_name
     * @return Wifi
     */
    public function setEnclosureName(?string $enclosure_name): Wifi
    {
        $this->enclosure_name = $enclosure_name;
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
     * @return Wifi
     */
    public function setNbU(?string $nb_u): Wifi
    {
        $this->nb_u = $nb_u;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerAId(): ?string
    {
        return $this->powerA_id;
    }

    /**
     * @param string|null $powerA_id
     * @return Wifi
     */
    public function setPowerAId(?string $powerA_id): Wifi
    {
        $this->powerA_id = $powerA_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerAName(): ?string
    {
        return $this->powerA_name;
    }

    /**
     * @param string|null $powerA_name
     * @return Wifi
     */
    public function setPowerAName(?string $powerA_name): Wifi
    {
        $this->powerA_name = $powerA_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerBId(): ?string
    {
        return $this->powerB_id;
    }

    /**
     * @param string|null $powerB_id
     * @return Wifi
     */
    public function setPowerBId(?string $powerB_id): Wifi
    {
        $this->powerB_id = $powerB_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerBName(): ?string
    {
        return $this->powerB_name;
    }

    /**
     * @param string|null $powerB_name
     * @return Wifi
     */
    public function setPowerBName(?string $powerB_name): Wifi
    {
        $this->powerB_name = $powerB_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFiberinterfacelistList(): ?string
    {
        return $this->fiberinterfacelist_list;
    }

    /**
     * @param string|null $fiberinterfacelist_list
     * @return Wifi
     */
    public function setFiberinterfacelistList(?string $fiberinterfacelist_list): Wifi
    {
        $this->fiberinterfacelist_list = $fiberinterfacelist_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSanList(): ?string
    {
        return $this->san_list;
    }

    /**
     * @param string|null $san_list
     * @return Wifi
     */
    public function setSanList(?string $san_list): Wifi
    {
        $this->san_list = $san_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedundancy(): ?string
    {
        return $this->redundancy;
    }

    /**
     * @param string|null $redundancy
     * @return Wifi
     */
    public function setRedundancy(?string $redundancy): Wifi
    {
        $this->redundancy = $redundancy;
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
     * @return Wifi
     */
    public function setPositionV(?string $position_v): Wifi
    {
        $this->position_v = $position_v;
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
     * @return Wifi
     */
    public function setPositionP(?string $position_p): Wifi
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
     * @return Wifi
     */
    public function setZeroU(?string $zero_u): Wifi
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
     * @return Wifi
     */
    public function setPositionH(?string $position_h): Wifi
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
     * @return Wifi
     */
    public function setNbCols(?string $nb_cols): Wifi
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
     * @return Wifi
     */
    public function setWeight(?string $weight): Wifi
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExpectedPowerInput(): ?string
    {
        return $this->expected_power_input;
    }

    /**
     * @param string|null $expected_power_input
     * @return Wifi
     */
    public function setExpectedPowerInput(?string $expected_power_input): Wifi
    {
        $this->expected_power_input = $expected_power_input;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getManagementipId(): ?string
    {
        return $this->managementip_id;
    }

    /**
     * @param string|null $managementip_id
     * @return Wifi
     */
    public function setManagementipId(?string $managementip_id): Wifi
    {
        $this->managementip_id = $managementip_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getManagementipName(): ?string
    {
        return $this->managementip_name;
    }

    /**
     * @param string|null $managementip_name
     * @return Wifi
     */
    public function setManagementipName(?string $managementip_name): Wifi
    {
        $this->managementip_name = $managementip_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkAId(): ?string
    {
        return $this->networkA_id;
    }

    /**
     * @param string|null $networkA_id
     * @return Wifi
     */
    public function setNetworkAId(?string $networkA_id): Wifi
    {
        $this->networkA_id = $networkA_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkAName(): ?string
    {
        return $this->networkA_name;
    }

    /**
     * @param string|null $networkA_name
     * @return Wifi
     */
    public function setNetworkAName(?string $networkA_name): Wifi
    {
        $this->networkA_name = $networkA_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkBId(): ?string
    {
        return $this->networkB_id;
    }

    /**
     * @param string|null $networkB_id
     * @return Wifi
     */
    public function setNetworkBId(?string $networkB_id): Wifi
    {
        $this->networkB_id = $networkB_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkBName(): ?string
    {
        return $this->networkB_name;
    }

    /**
     * @param string|null $networkB_name
     * @return Wifi
     */
    public function setNetworkBName(?string $networkB_name): Wifi
    {
        $this->networkB_name = $networkB_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedundancyNetwork(): ?string
    {
        return $this->redundancy_network;
    }

    /**
     * @param string|null $redundancy_network
     * @return Wifi
     */
    public function setRedundancyNetwork(?string $redundancy_network): Wifi
    {
        $this->redundancy_network = $redundancy_network;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkdevicetypeId(): ?string
    {
        return $this->networkdevicetype_id;
    }

    /**
     * @param string|null $networkdevicetype_id
     * @return Wifi
     */
    public function setNetworkdevicetypeId(?string $networkdevicetype_id): Wifi
    {
        $this->networkdevicetype_id = $networkdevicetype_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkdevicetypeName(): ?string
    {
        return $this->networkdevicetype_name;
    }

    /**
     * @param string|null $networkdevicetype_name
     * @return Wifi
     */
    public function setNetworkdevicetypeName(?string $networkdevicetype_name): Wifi
    {
        $this->networkdevicetype_name = $networkdevicetype_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConnectablecisList(): ?string
    {
        return $this->connectablecis_list;
    }

    /**
     * @param string|null $connectablecis_list
     * @return Wifi
     */
    public function setConnectablecisList(?string $connectablecis_list): Wifi
    {
        $this->connectablecis_list = $connectablecis_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIosversionId(): ?string
    {
        return $this->iosversion_id;
    }

    /**
     * @param string|null $iosversion_id
     * @return Wifi
     */
    public function setIosversionId(?string $iosversion_id): Wifi
    {
        $this->iosversion_id = $iosversion_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIosversionName(): ?string
    {
        return $this->iosversion_name;
    }

    /**
     * @param string|null $iosversion_name
     * @return Wifi
     */
    public function setIosversionName(?string $iosversion_name): Wifi
    {
        $this->iosversion_name = $iosversion_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRam(): ?string
    {
        return $this->ram;
    }

    /**
     * @param string|null $ram
     * @return Wifi
     */
    public function setRam(?string $ram): Wifi
    {
        $this->ram = $ram;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkdevicevirtualinterfacesList(): ?string
    {
        return $this->networkdevicevirtualinterfaces_list;
    }

    /**
     * @param string|null $networkdevicevirtualinterfaces_list
     * @return Wifi
     */
    public function setNetworkdevicevirtualinterfacesList(?string $networkdevicevirtualinterfaces_list): Wifi
    {
        $this->networkdevicevirtualinterfaces_list = $networkdevicevirtualinterfaces_list;
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
     * @return Wifi
     */
    public function setFqdn(?string $fqdn): Wifi
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTest(): ?string
    {
        return $this->test;
    }

    /**
     * @param string|null $test
     * @return Wifi
     */
    public function setTest(?string $test): Wifi
    {
        $this->test = $test;
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
     * @return Wifi
     */
    public function setFinalclass(string $finalclass): Wifi
    {
        $this->finalclass = $finalclass;
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
     * @return Wifi
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): Wifi
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
     * @return Wifi
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): Wifi
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
     * @return Wifi
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): Wifi
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
     * @return Wifi
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): Wifi
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
     * @return Wifi
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): Wifi
    {
        $this->model_id_friendlyname = $model_id_friendlyname;
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
     * @return Wifi
     */
    public function setRackIdFriendlyname(?string $rack_id_friendlyname): Wifi
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
     * @return Wifi
     */
    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): Wifi
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
     * @return Wifi
     */
    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): Wifi
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
     * @return Wifi
     */
    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): Wifi
    {
        $this->enclosure_id_obsolescence_flag = $enclosure_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerAIdFriendlyname(): ?string
    {
        return $this->powerA_id_friendlyname;
    }

    /**
     * @param string|null $powerA_id_friendlyname
     * @return Wifi
     */
    public function setPowerAIdFriendlyname(?string $powerA_id_friendlyname): Wifi
    {
        $this->powerA_id_friendlyname = $powerA_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerAIdFinalclassRecall(): ?string
    {
        return $this->powerA_id_finalclass_recall;
    }

    /**
     * @param string|null $powerA_id_finalclass_recall
     * @return Wifi
     */
    public function setPowerAIdFinalclassRecall(?string $powerA_id_finalclass_recall): Wifi
    {
        $this->powerA_id_finalclass_recall = $powerA_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerAIdObsolescenceFlag(): ?string
    {
        return $this->powerA_id_obsolescence_flag;
    }

    /**
     * @param string|null $powerA_id_obsolescence_flag
     * @return Wifi
     */
    public function setPowerAIdObsolescenceFlag(?string $powerA_id_obsolescence_flag): Wifi
    {
        $this->powerA_id_obsolescence_flag = $powerA_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerBIdFriendlyname(): ?string
    {
        return $this->powerB_id_friendlyname;
    }

    /**
     * @param string|null $powerB_id_friendlyname
     * @return Wifi
     */
    public function setPowerBIdFriendlyname(?string $powerB_id_friendlyname): Wifi
    {
        $this->powerB_id_friendlyname = $powerB_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerBIdFinalclassRecall(): ?string
    {
        return $this->powerB_id_finalclass_recall;
    }

    /**
     * @param string|null $powerB_id_finalclass_recall
     * @return Wifi
     */
    public function setPowerBIdFinalclassRecall(?string $powerB_id_finalclass_recall): Wifi
    {
        $this->powerB_id_finalclass_recall = $powerB_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerBIdObsolescenceFlag(): ?string
    {
        return $this->powerB_id_obsolescence_flag;
    }

    /**
     * @param string|null $powerB_id_obsolescence_flag
     * @return Wifi
     */
    public function setPowerBIdObsolescenceFlag(?string $powerB_id_obsolescence_flag): Wifi
    {
        $this->powerB_id_obsolescence_flag = $powerB_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getManagementipIdFriendlyname(): ?string
    {
        return $this->managementip_id_friendlyname;
    }

    /**
     * @param string|null $managementip_id_friendlyname
     * @return Wifi
     */
    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): Wifi
    {
        $this->managementip_id_friendlyname = $managementip_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getManagementipIdFinalclassRecall(): ?string
    {
        return $this->managementip_id_finalclass_recall;
    }

    /**
     * @param string|null $managementip_id_finalclass_recall
     * @return Wifi
     */
    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): Wifi
    {
        $this->managementip_id_finalclass_recall = $managementip_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkAIdFriendlyname(): ?string
    {
        return $this->networkA_id_friendlyname;
    }

    /**
     * @param string|null $networkA_id_friendlyname
     * @return Wifi
     */
    public function setNetworkAIdFriendlyname(?string $networkA_id_friendlyname): Wifi
    {
        $this->networkA_id_friendlyname = $networkA_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkAIdObsolescenceFlag(): ?string
    {
        return $this->networkA_id_obsolescence_flag;
    }

    /**
     * @param string|null $networkA_id_obsolescence_flag
     * @return Wifi
     */
    public function setNetworkAIdObsolescenceFlag(?string $networkA_id_obsolescence_flag): Wifi
    {
        $this->networkA_id_obsolescence_flag = $networkA_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkBIdFriendlyname(): ?string
    {
        return $this->networkB_id_friendlyname;
    }

    /**
     * @param string|null $networkB_id_friendlyname
     * @return Wifi
     */
    public function setNetworkBIdFriendlyname(?string $networkB_id_friendlyname): Wifi
    {
        $this->networkB_id_friendlyname = $networkB_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkBIdObsolescenceFlag(): ?string
    {
        return $this->networkB_id_obsolescence_flag;
    }

    /**
     * @param string|null $networkB_id_obsolescence_flag
     * @return Wifi
     */
    public function setNetworkBIdObsolescenceFlag(?string $networkB_id_obsolescence_flag): Wifi
    {
        $this->networkB_id_obsolescence_flag = $networkB_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkdevicetypeIdFriendlyname(): ?string
    {
        return $this->networkdevicetype_id_friendlyname;
    }

    /**
     * @param string|null $networkdevicetype_id_friendlyname
     * @return Wifi
     */
    public function setNetworkdevicetypeIdFriendlyname(?string $networkdevicetype_id_friendlyname): Wifi
    {
        $this->networkdevicetype_id_friendlyname = $networkdevicetype_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIosversionIdFriendlyname(): ?string
    {
        return $this->iosversion_id_friendlyname;
    }

    /**
     * @param string|null $iosversion_id_friendlyname
     * @return Wifi
     */
    public function setIosversionIdFriendlyname(?string $iosversion_id_friendlyname): Wifi
    {
        $this->iosversion_id_friendlyname = $iosversion_id_friendlyname;
        return $this;
    }
}