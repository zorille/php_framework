<?php

namespace Zorille\itop\data_models;

class NetworkDevice extends FunctionalCI
{
    const ENTITY_NAME = 'NetworkDevice';

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
    protected string $finalclass = 'NetworkDevice';
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
     * @return NetworkDevice
     */
    public function setOverview(?string $overview): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setMonitoringList(?string $monitoring_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setSerialnumber(?string $serialnumber): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setLocationId(?string $location_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setLocationName(?string $location_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setStatus(?string $status): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setBrandId(?string $brand_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setBrandName(?string $brand_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setModelId(?string $model_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setModelName(?string $model_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setAssetNumber(?string $asset_number): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPurchaseDate(?string $purchase_date): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setEndOfWarranty(?string $end_of_warranty): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setMaintenanceDate(?string $maintenance_date): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkdeviceList(?string $networkdevice_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPhysicalinterfaceList(?string $physicalinterface_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRackId(?string $rack_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRackName(?string $rack_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setEnclosureId(?string $enclosure_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setEnclosureName(?string $enclosure_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNbU(?string $nb_u): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerAId(?string $powerA_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerAName(?string $powerA_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerBId(?string $powerB_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerBName(?string $powerB_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setFiberinterfacelistList(?string $fiberinterfacelist_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setSanList(?string $san_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRedundancy(?string $redundancy): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPositionV(?string $position_v): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPositionP(?string $position_p): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setZeroU(?string $zero_u): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPositionH(?string $position_h): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNbCols(?string $nb_cols): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setWeight(?string $weight): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setExpectedPowerInput(?string $expected_power_input): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setManagementipId(?string $managementip_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setManagementipName(?string $managementip_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkAId(?string $networkA_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkAName(?string $networkA_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkBId(?string $networkB_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkBName(?string $networkB_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRedundancyNetwork(?string $redundancy_network): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkdevicetypeId(?string $networkdevicetype_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkdevicetypeName(?string $networkdevicetype_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setConnectablecisList(?string $connectablecis_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setIosversionId(?string $iosversion_id): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setIosversionName(?string $iosversion_name): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRam(?string $ram): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkdevicevirtualinterfacesList(?string $networkdevicevirtualinterfaces_list): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setFqdn(?string $fqdn): NetworkDevice
    {
        $this->fqdn = $fqdn;
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
     * @return NetworkDevice
     */
    public function setFinalclass(string $finalclass): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRackIdFriendlyname(?string $rack_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerAIdFriendlyname(?string $powerA_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerAIdFinalclassRecall(?string $powerA_id_finalclass_recall): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerAIdObsolescenceFlag(?string $powerA_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerBIdFriendlyname(?string $powerB_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerBIdFinalclassRecall(?string $powerB_id_finalclass_recall): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setPowerBIdObsolescenceFlag(?string $powerB_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkAIdFriendlyname(?string $networkA_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkAIdObsolescenceFlag(?string $networkA_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkBIdFriendlyname(?string $networkB_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkBIdObsolescenceFlag(?string $networkB_id_obsolescence_flag): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setNetworkdevicetypeIdFriendlyname(?string $networkdevicetype_id_friendlyname): NetworkDevice
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
     * @return NetworkDevice
     */
    public function setIosversionIdFriendlyname(?string $iosversion_id_friendlyname): NetworkDevice
    {
        $this->iosversion_id_friendlyname = $iosversion_id_friendlyname;
        return $this;
    }
}