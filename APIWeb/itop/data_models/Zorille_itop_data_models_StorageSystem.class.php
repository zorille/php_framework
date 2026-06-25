<?php

namespace Zorille\itop\data_models;

class StorageSystem extends PhysicalDevice
{
    protected ?string $overview = null;
    protected ?string $monitoring_list = null;
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
    protected ?string $kg = null;
    protected ?string $W = null;
    protected ?string $managementip_id = null;
    protected ?string $managementip_name = null;
    protected ?string $networkA_id = null;
    protected ?string $networkA_name = null;
    protected ?string $networkB_id = null;
    protected ?string $networkB_name = null;
    protected ?string $redundancy_network = null;
    protected ?string $logicalvolume_list = null;
    protected ?string $fqdn = null;
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
    protected string $finalclass = 'StorageSystem';

    public function getPhysicalinterfaceList(): ?string
    {
        return $this->physicalinterface_list;
    }

    public function setPhysicalinterfaceList(?string $physicalinterface_list): StorageSystem
    {
        $this->physicalinterface_list = $physicalinterface_list;
        return $this;
    }

    public function getRackId(): ?string
    {
        return $this->rack_id;
    }

    public function setRackId(?string $rack_id): StorageSystem
    {
        $this->rack_id = $rack_id;
        return $this;
    }

    public function getRackName(): ?string
    {
        return $this->rack_name;
    }

    public function setRackName(?string $rack_name): StorageSystem
    {
        $this->rack_name = $rack_name;
        return $this;
    }

    public function getEnclosureId(): ?string
    {
        return $this->enclosure_id;
    }

    public function setEnclosureId(?string $enclosure_id): StorageSystem
    {
        $this->enclosure_id = $enclosure_id;
        return $this;
    }

    public function getEnclosureName(): ?string
    {
        return $this->enclosure_name;
    }

    public function setEnclosureName(?string $enclosure_name): StorageSystem
    {
        $this->enclosure_name = $enclosure_name;
        return $this;
    }

    public function getNbU(): ?string
    {
        return $this->nb_u;
    }

    public function setNbU(?string $nb_u): StorageSystem
    {
        $this->nb_u = $nb_u;
        return $this;
    }

    public function getPowerAId(): ?string
    {
        return $this->powerA_id;
    }

    public function setPowerAId(?string $powerA_id): StorageSystem
    {
        $this->powerA_id = $powerA_id;
        return $this;
    }

    public function getPowerAName(): ?string
    {
        return $this->powerA_name;
    }

    public function setPowerAName(?string $powerA_name): StorageSystem
    {
        $this->powerA_name = $powerA_name;
        return $this;
    }

    public function getPowerBId(): ?string
    {
        return $this->powerB_id;
    }

    public function setPowerBId(?string $powerB_id): StorageSystem
    {
        $this->powerB_id = $powerB_id;
        return $this;
    }

    public function getPowerBName(): ?string
    {
        return $this->powerB_name;
    }

    public function setPowerBName(?string $powerB_name): StorageSystem
    {
        $this->powerB_name = $powerB_name;
        return $this;
    }

    public function getFiberinterfacelistList(): ?string
    {
        return $this->fiberinterfacelist_list;
    }

    public function setFiberinterfacelistList(?string $fiberinterfacelist_list): StorageSystem
    {
        $this->fiberinterfacelist_list = $fiberinterfacelist_list;
        return $this;
    }

    public function getSanList(): ?string
    {
        return $this->san_list;
    }

    public function setSanList(?string $san_list): StorageSystem
    {
        $this->san_list = $san_list;
        return $this;
    }

    public function getRedundancy(): ?string
    {
        return $this->redundancy;
    }

    public function setRedundancy(?string $redundancy): StorageSystem
    {
        $this->redundancy = $redundancy;
        return $this;
    }

    public function getPositionV(): ?string
    {
        return $this->position_v;
    }

    public function setPositionV(?string $position_v): StorageSystem
    {
        $this->position_v = $position_v;
        return $this;
    }

    public function getPositionP(): ?string
    {
        return $this->position_p;
    }

    public function setPositionP(?string $position_p): StorageSystem
    {
        $this->position_p = $position_p;
        return $this;
    }

    public function getZeroU(): ?string
    {
        return $this->zero_u;
    }

    public function setZeroU(?string $zero_u): StorageSystem
    {
        $this->zero_u = $zero_u;
        return $this;
    }

    public function getPositionH(): ?string
    {
        return $this->position_h;
    }

    public function setPositionH(?string $position_h): StorageSystem
    {
        $this->position_h = $position_h;
        return $this;
    }

    public function getNbCols(): ?string
    {
        return $this->nb_cols;
    }

    public function setNbCols(?string $nb_cols): StorageSystem
    {
        $this->nb_cols = $nb_cols;
        return $this;
    }

    public function getKg(): ?string
    {
        return $this->kg;
    }

    public function setKg(?string $kg): StorageSystem
    {
        $this->kg = $kg;
        return $this;
    }

    public function getW(): ?string
    {
        return $this->W;
    }

    public function setW(?string $W): StorageSystem
    {
        $this->W = $W;
        return $this;
    }

    public function getManagementipId(): ?string
    {
        return $this->managementip_id;
    }

    public function setManagementipId(?string $managementip_id): StorageSystem
    {
        $this->managementip_id = $managementip_id;
        return $this;
    }

    public function getManagementipName(): ?string
    {
        return $this->managementip_name;
    }

    public function setManagementipName(?string $managementip_name): StorageSystem
    {
        $this->managementip_name = $managementip_name;
        return $this;
    }

    public function getNetworkAId(): ?string
    {
        return $this->networkA_id;
    }

    public function setNetworkAId(?string $networkA_id): StorageSystem
    {
        $this->networkA_id = $networkA_id;
        return $this;
    }

    public function getNetworkAName(): ?string
    {
        return $this->networkA_name;
    }

    public function setNetworkAName(?string $networkA_name): StorageSystem
    {
        $this->networkA_name = $networkA_name;
        return $this;
    }

    public function getNetworkBId(): ?string
    {
        return $this->networkB_id;
    }

    public function setNetworkBId(?string $networkB_id): StorageSystem
    {
        $this->networkB_id = $networkB_id;
        return $this;
    }

    public function getNetworkBName(): ?string
    {
        return $this->networkB_name;
    }

    public function setNetworkBName(?string $networkB_name): StorageSystem
    {
        $this->networkB_name = $networkB_name;
        return $this;
    }

    public function getRedundancyNetwork(): ?string
    {
        return $this->redundancy_network;
    }

    public function setRedundancyNetwork(?string $redundancy_network): StorageSystem
    {
        $this->redundancy_network = $redundancy_network;
        return $this;
    }

    public function getLogicalvolumeList(): ?string
    {
        return $this->logicalvolume_list;
    }

    public function setLogicalvolumeList(?string $logicalvolume_list): StorageSystem
    {
        $this->logicalvolume_list = $logicalvolume_list;
        return $this;
    }

    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    public function setFqdn(?string $fqdn): StorageSystem
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    public function getRackIdFriendlyname(): ?string
    {
        return $this->rack_id_friendlyname;
    }

    public function setRackIdFriendlyname(?string $rack_id_friendlyname): StorageSystem
    {
        $this->rack_id_friendlyname = $rack_id_friendlyname;
        return $this;
    }

    public function getRackIdObsolescenceFlag(): ?string
    {
        return $this->rack_id_obsolescence_flag;
    }

    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): StorageSystem
    {
        $this->rack_id_obsolescence_flag = $rack_id_obsolescence_flag;
        return $this;
    }

    public function getEnclosureIdFriendlyname(): ?string
    {
        return $this->enclosure_id_friendlyname;
    }

    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): StorageSystem
    {
        $this->enclosure_id_friendlyname = $enclosure_id_friendlyname;
        return $this;
    }

    public function getEnclosureIdObsolescenceFlag(): ?string
    {
        return $this->enclosure_id_obsolescence_flag;
    }

    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): StorageSystem
    {
        $this->enclosure_id_obsolescence_flag = $enclosure_id_obsolescence_flag;
        return $this;
    }

    public function getPowerAIdFriendlyname(): ?string
    {
        return $this->powerA_id_friendlyname;
    }

    public function setPowerAIdFriendlyname(?string $powerA_id_friendlyname): StorageSystem
    {
        $this->powerA_id_friendlyname = $powerA_id_friendlyname;
        return $this;
    }

    public function getPowerAIdFinalclassRecall(): ?string
    {
        return $this->powerA_id_finalclass_recall;
    }

    public function setPowerAIdFinalclassRecall(?string $powerA_id_finalclass_recall): StorageSystem
    {
        $this->powerA_id_finalclass_recall = $powerA_id_finalclass_recall;
        return $this;
    }

    public function getPowerAIdObsolescenceFlag(): ?string
    {
        return $this->powerA_id_obsolescence_flag;
    }

    public function setPowerAIdObsolescenceFlag(?string $powerA_id_obsolescence_flag): StorageSystem
    {
        $this->powerA_id_obsolescence_flag = $powerA_id_obsolescence_flag;
        return $this;
    }

    public function getPowerBIdFriendlyname(): ?string
    {
        return $this->powerB_id_friendlyname;
    }

    public function setPowerBIdFriendlyname(?string $powerB_id_friendlyname): StorageSystem
    {
        $this->powerB_id_friendlyname = $powerB_id_friendlyname;
        return $this;
    }

    public function getPowerBIdFinalclassRecall(): ?string
    {
        return $this->powerB_id_finalclass_recall;
    }

    public function setPowerBIdFinalclassRecall(?string $powerB_id_finalclass_recall): StorageSystem
    {
        $this->powerB_id_finalclass_recall = $powerB_id_finalclass_recall;
        return $this;
    }

    public function getPowerBIdObsolescenceFlag(): ?string
    {
        return $this->powerB_id_obsolescence_flag;
    }

    public function setPowerBIdObsolescenceFlag(?string $powerB_id_obsolescence_flag): StorageSystem
    {
        $this->powerB_id_obsolescence_flag = $powerB_id_obsolescence_flag;
        return $this;
    }

    public function getManagementipIdFriendlyname(): ?string
    {
        return $this->managementip_id_friendlyname;
    }

    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): StorageSystem
    {
        $this->managementip_id_friendlyname = $managementip_id_friendlyname;
        return $this;
    }

    public function getManagementipIdFinalclassRecall(): ?string
    {
        return $this->managementip_id_finalclass_recall;
    }

    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): StorageSystem
    {
        $this->managementip_id_finalclass_recall = $managementip_id_finalclass_recall;
        return $this;
    }

    public function getNetworkAIdFriendlyname(): ?string
    {
        return $this->networkA_id_friendlyname;
    }

    public function setNetworkAIdFriendlyname(?string $networkA_id_friendlyname): StorageSystem
    {
        $this->networkA_id_friendlyname = $networkA_id_friendlyname;
        return $this;
    }

    public function getNetworkAIdObsolescenceFlag(): ?string
    {
        return $this->networkA_id_obsolescence_flag;
    }

    public function setNetworkAIdObsolescenceFlag(?string $networkA_id_obsolescence_flag): StorageSystem
    {
        $this->networkA_id_obsolescence_flag = $networkA_id_obsolescence_flag;
        return $this;
    }

    public function getNetworkBIdFriendlyname(): ?string
    {
        return $this->networkB_id_friendlyname;
    }

    public function setNetworkBIdFriendlyname(?string $networkB_id_friendlyname): StorageSystem
    {
        $this->networkB_id_friendlyname = $networkB_id_friendlyname;
        return $this;
    }

    public function getNetworkBIdObsolescenceFlag(): ?string
    {
        return $this->networkB_id_obsolescence_flag;
    }

    public function setNetworkBIdObsolescenceFlag(?string $networkB_id_obsolescence_flag): StorageSystem
    {
        $this->networkB_id_obsolescence_flag = $networkB_id_obsolescence_flag;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): StorageSystem
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}