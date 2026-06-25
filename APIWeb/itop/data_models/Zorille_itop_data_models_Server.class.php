<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class Server extends data_model
{
    const ENTITY_NAME = 'Server';

    protected ?string $id = null;
    protected ?string $name = null;
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
    protected ?string $osfamily_id = null;
    protected ?string $osfamily_name = null;
    protected ?string $osversion_id = null;
    protected ?string $osversion_name = null;
    protected ?string $oslicence_id = null;
    protected ?string $oslicence_name = null;
    protected ?string $cpu = null;
    protected ?string $ram = null;
    protected ?string $logicalvolumes_list = null;
    protected ?string $ocs_oscomment = null;
    protected ?string $ocs_id = null;
    protected ?string $cvss = null;
    protected ?string $fqdn = null;
    protected ?string $hypervisor_list = null;
    protected ?string $friendlyname = null;
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
    protected ?string $osfamily_id_friendlyname = null;
    protected ?string $osversion_id_friendlyname = null;
    protected ?string $oslicence_id_friendlyname = null;
    protected ?string $oslicence_id_obsolescence_flag = null;
    protected string $finalclass = 'Server';

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return Server
     */
    public function setId(?string $id): Server
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Server
     */
    public function setName(?string $name): Server
    {
        $this->name = $name;
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
     * @return Server
     */
    public function setSerialnumber(?string $serialnumber): Server
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
     * @return Server
     */
    public function setLocationId(?string $location_id): Server
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
     * @return Server
     */
    public function setLocationName(?string $location_name): Server
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
     * @return Server
     */
    public function setStatus(?string $status): Server
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
     * @return Server
     */
    public function setBrandId(?string $brand_id): Server
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
     * @return Server
     */
    public function setBrandName(?string $brand_name): Server
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
     * @return Server
     */
    public function setModelId(?string $model_id): Server
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
     * @return Server
     */
    public function setModelName(?string $model_name): Server
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
     * @return Server
     */
    public function setAssetNumber(?string $asset_number): Server
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
     * @return Server
     */
    public function setPurchaseDate(?string $purchase_date): Server
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
     * @return Server
     */
    public function setEndOfWarranty(?string $end_of_warranty): Server
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
     * @return Server
     */
    public function setMaintenanceDate(?string $maintenance_date): Server
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
     * @return Server
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): Server
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
     * @return Server
     */
    public function setNetworkdeviceList(?string $networkdevice_list): Server
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
     * @return Server
     */
    public function setPhysicalinterfaceList(?string $physicalinterface_list): Server
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
     * @return Server
     */
    public function setRackId(?string $rack_id): Server
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
     * @return Server
     */
    public function setRackName(?string $rack_name): Server
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
     * @return Server
     */
    public function setEnclosureId(?string $enclosure_id): Server
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
     * @return Server
     */
    public function setEnclosureName(?string $enclosure_name): Server
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
     * @return Server
     */
    public function setNbU(?string $nb_u): Server
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
     * @return Server
     */
    public function setPowerAId(?string $powerA_id): Server
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
     * @return Server
     */
    public function setPowerAName(?string $powerA_name): Server
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
     * @return Server
     */
    public function setPowerBId(?string $powerB_id): Server
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
     * @return Server
     */
    public function setPowerBName(?string $powerB_name): Server
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
     * @return Server
     */
    public function setFiberinterfacelistList(?string $fiberinterfacelist_list): Server
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
     * @return Server
     */
    public function setSanList(?string $san_list): Server
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
     * @return Server
     */
    public function setRedundancy(?string $redundancy): Server
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
     * @return Server
     */
    public function setPositionV(?string $position_v): Server
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
     * @return Server
     */
    public function setPositionP(?string $position_p): Server
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
     * @return Server
     */
    public function setZeroU(?string $zero_u): Server
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
     * @return Server
     */
    public function setPositionH(?string $position_h): Server
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
     * @return Server
     */
    public function setNbCols(?string $nb_cols): Server
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
     * @return Server
     */
    public function setWeight(?string $weight): Server
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
     * @return Server
     */
    public function setExpectedPowerInput(?string $expected_power_input): Server
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
     * @return Server
     */
    public function setManagementipId(?string $managementip_id): Server
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
     * @return Server
     */
    public function setManagementipName(?string $managementip_name): Server
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
     * @return Server
     */
    public function setNetworkAId(?string $networkA_id): Server
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
     * @return Server
     */
    public function setNetworkAName(?string $networkA_name): Server
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
     * @return Server
     */
    public function setNetworkBId(?string $networkB_id): Server
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
     * @return Server
     */
    public function setNetworkBName(?string $networkB_name): Server
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
     * @return Server
     */
    public function setRedundancyNetwork(?string $redundancy_network): Server
    {
        $this->redundancy_network = $redundancy_network;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsfamilyId(): ?string
    {
        return $this->osfamily_id;
    }

    /**
     * @param string|null $osfamily_id
     * @return Server
     */
    public function setOsfamilyId(?string $osfamily_id): Server
    {
        $this->osfamily_id = $osfamily_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsfamilyName(): ?string
    {
        return $this->osfamily_name;
    }

    /**
     * @param string|null $osfamily_name
     * @return Server
     */
    public function setOsfamilyName(?string $osfamily_name): Server
    {
        $this->osfamily_name = $osfamily_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsversionId(): ?string
    {
        return $this->osversion_id;
    }

    /**
     * @param string|null $osversion_id
     * @return Server
     */
    public function setOsversionId(?string $osversion_id): Server
    {
        $this->osversion_id = $osversion_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsversionName(): ?string
    {
        return $this->osversion_name;
    }

    /**
     * @param string|null $osversion_name
     * @return Server
     */
    public function setOsversionName(?string $osversion_name): Server
    {
        $this->osversion_name = $osversion_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOslicenceId(): ?string
    {
        return $this->oslicence_id;
    }

    /**
     * @param string|null $oslicence_id
     * @return Server
     */
    public function setOslicenceId(?string $oslicence_id): Server
    {
        $this->oslicence_id = $oslicence_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOslicenceName(): ?string
    {
        return $this->oslicence_name;
    }

    /**
     * @param string|null $oslicence_name
     * @return Server
     */
    public function setOslicenceName(?string $oslicence_name): Server
    {
        $this->oslicence_name = $oslicence_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCpu(): ?string
    {
        return $this->cpu;
    }

    /**
     * @param string|null $cpu
     * @return Server
     */
    public function setCpu(?string $cpu): Server
    {
        $this->cpu = $cpu;
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
     * @return Server
     */
    public function setRam(?string $ram): Server
    {
        $this->ram = $ram;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogicalvolumesList(): ?string
    {
        return $this->logicalvolumes_list;
    }

    /**
     * @param string|null $logicalvolumes_list
     * @return Server
     */
    public function setLogicalvolumesList(?string $logicalvolumes_list): Server
    {
        $this->logicalvolumes_list = $logicalvolumes_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOcsOscomment(): ?string
    {
        return $this->ocs_oscomment;
    }

    /**
     * @param string|null $ocs_oscomment
     * @return Server
     */
    public function setOcsOscomment(?string $ocs_oscomment): Server
    {
        $this->ocs_oscomment = $ocs_oscomment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOcsId(): ?string
    {
        return $this->ocs_id;
    }

    /**
     * @param string|null $ocs_id
     * @return Server
     */
    public function setOcsId(?string $ocs_id): Server
    {
        $this->ocs_id = $ocs_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCvss(): ?string
    {
        return $this->cvss;
    }

    /**
     * @param string|null $cvss
     * @return Server
     */
    public function setCvss(?string $cvss): Server
    {
        $this->cvss = $cvss;
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
     * @return Server
     */
    public function setFqdn(?string $fqdn): Server
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHypervisorList(): ?string
    {
        return $this->hypervisor_list;
    }

    /**
     * @param string|null $hypervisor_list
     * @return Server
     */
    public function setHypervisorList(?string $hypervisor_list): Server
    {
        $this->hypervisor_list = $hypervisor_list;
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
     * @return Server
     */
    public function setFriendlyname(?string $friendlyname): Server
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
     * @return Server
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): Server
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
     * @return Server
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): Server
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
     * @return Server
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): Server
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
     * @return Server
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): Server
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
     * @return Server
     */
    public function setRackIdFriendlyname(?string $rack_id_friendlyname): Server
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
     * @return Server
     */
    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): Server
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
     * @return Server
     */
    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setPowerAIdFriendlyname(?string $powerA_id_friendlyname): Server
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
     * @return Server
     */
    public function setPowerAIdFinalclassRecall(?string $powerA_id_finalclass_recall): Server
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
     * @return Server
     */
    public function setPowerAIdObsolescenceFlag(?string $powerA_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setPowerBIdFriendlyname(?string $powerB_id_friendlyname): Server
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
     * @return Server
     */
    public function setPowerBIdFinalclassRecall(?string $powerB_id_finalclass_recall): Server
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
     * @return Server
     */
    public function setPowerBIdObsolescenceFlag(?string $powerB_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): Server
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
     * @return Server
     */
    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): Server
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
     * @return Server
     */
    public function setNetworkAIdFriendlyname(?string $networkA_id_friendlyname): Server
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
     * @return Server
     */
    public function setNetworkAIdObsolescenceFlag(?string $networkA_id_obsolescence_flag): Server
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
     * @return Server
     */
    public function setNetworkBIdFriendlyname(?string $networkB_id_friendlyname): Server
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
     * @return Server
     */
    public function setNetworkBIdObsolescenceFlag(?string $networkB_id_obsolescence_flag): Server
    {
        $this->networkB_id_obsolescence_flag = $networkB_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsfamilyIdFriendlyname(): ?string
    {
        return $this->osfamily_id_friendlyname;
    }

    /**
     * @param string|null $osfamily_id_friendlyname
     * @return Server
     */
    public function setOsfamilyIdFriendlyname(?string $osfamily_id_friendlyname): Server
    {
        $this->osfamily_id_friendlyname = $osfamily_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOsversionIdFriendlyname(): ?string
    {
        return $this->osversion_id_friendlyname;
    }

    /**
     * @param string|null $osversion_id_friendlyname
     * @return Server
     */
    public function setOsversionIdFriendlyname(?string $osversion_id_friendlyname): Server
    {
        $this->osversion_id_friendlyname = $osversion_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOslicenceIdFriendlyname(): ?string
    {
        return $this->oslicence_id_friendlyname;
    }

    /**
     * @param string|null $oslicence_id_friendlyname
     * @return Server
     */
    public function setOslicenceIdFriendlyname(?string $oslicence_id_friendlyname): Server
    {
        $this->oslicence_id_friendlyname = $oslicence_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOslicenceIdObsolescenceFlag(): ?string
    {
        return $this->oslicence_id_obsolescence_flag;
    }

    /**
     * @param string|null $oslicence_id_obsolescence_flag
     * @return Server
     */
    public function setOslicenceIdObsolescenceFlag(?string $oslicence_id_obsolescence_flag): Server
    {
        $this->oslicence_id_obsolescence_flag = $oslicence_id_obsolescence_flag;
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
     * @return Server
     */
    public function setFinalclass(string $finalclass): Server
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}