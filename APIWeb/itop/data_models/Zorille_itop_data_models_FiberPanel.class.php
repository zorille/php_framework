<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class FiberPanel extends PhysicalDevice {
    public array $networkdevice_list = [];
    public array $physicalinterface_list = [];
    public ?string $rack_id = null;
    public ?string $rack_name = null;
    public ?string $enclosure_id = null;
    public ?string $enclosure_name = null;
    public ?string $nb_u = null;
    public ?string $powerA_id = null;
    public ?string $powerA_name = null;
    public ?string $powerB_id = null;
    public ?string $powerB_name = null;
    public array $fiberinterfacelist_list = [];
    public array $san_list = [];
    public ?string $redundancy = null;
    public ?string $position_v = null;
    public ?string $position_p = null;
    public ?string $zero_u = null;
    public ?string $position_h = null;
    public ?string $nb_cols = null;
    public ?string $weight = null;
    public ?string $expected_power_input = null;
    public ?string $managementip_id = null;
    public ?string $managementip_name = null;
    public ?string $networkA_id = null;
    public ?string $networkA_name = null;
    public ?string $networkB_id = null;
    public ?string $networkB_name = null;
    public ?string $redundancy_network = null;
    public ?string $fiber_quantity = null;
    public array $pdl_list = [];
    public ?string $customer_id = null;
    public ?string $customer_name = null;
    public ?string $customer_codeclient = null;
    public ?string $rack_id_friendlyname = null;
    public ?string $rack_id_obsolescence_flag = null;
    public ?string $enclosure_id_friendlyname = null;
    public ?string $enclosure_id_obsolescence_flag = null;
    public ?string $powerA_id_friendlyname = null;
    public ?string $powerA_id_finalclass_recall = null;
    public ?string $powerA_id_obsolescence_flag = null;
    public ?string $powerB_id_friendlyname = null;
    public ?string $powerB_id_finalclass_recall = null;
    public ?string $powerB_id_obsolescence_flag = null;
    public ?string $managementip_id_friendlyname = null;
    public ?string $managementip_id_finalclass_recall = null;
    public ?string $networkA_id_friendlyname = null;
    public ?string $networkA_id_obsolescence_flag = null;
    public ?string $networkB_id_friendlyname = null;
    public ?string $networkB_id_obsolescence_flag = null;
    public ?string $customer_id_friendlyname = null;
    public ?string $customer_id_obsolescence_flag = null;

    public string $finalclass = 'FiberPanel';

    public function getNetworkdeviceList(): array
    {
        return $this->networkdevice_list;
    }

    public function setNetworkdeviceList(array $networkdevice_list): static
    {
        $this->networkdevice_list = $networkdevice_list;
        return $this;
    }

    public function getPhysicalinterfaceList(): array
    {
        return $this->physicalinterface_list;
    }

    public function setPhysicalinterfaceList(array $physicalinterface_list): static
    {
        $this->physicalinterface_list = $physicalinterface_list;
        return $this;
    }

    public function getRackId(): ?string
    {
        return $this->rack_id;
    }

    public function setRackId(?string $rack_id): static
    {
        $this->rack_id = $rack_id;
        return $this;
    }

    public function getRackName(): ?string
    {
        return $this->rack_name;
    }

    public function setRackName(?string $rack_name): static
    {
        $this->rack_name = $rack_name;
        return $this;
    }

    public function getEnclosureId(): ?string
    {
        return $this->enclosure_id;
    }

    public function setEnclosureId(?string $enclosure_id): static
    {
        $this->enclosure_id = $enclosure_id;
        return $this;
    }

    public function getEnclosureName(): ?string
    {
        return $this->enclosure_name;
    }

    public function setEnclosureName(?string $enclosure_name): static
    {
        $this->enclosure_name = $enclosure_name;
        return $this;
    }

    public function getNbU(): ?string
    {
        return $this->nb_u;
    }

    public function setNbU(?string $nb_u): static
    {
        $this->nb_u = $nb_u;
        return $this;
    }

    public function getPowerAId(): ?string
    {
        return $this->powerA_id;
    }

    public function setPowerAId(?string $powerA_id): static
    {
        $this->powerA_id = $powerA_id;
        return $this;
    }

    public function getPowerAName(): ?string
    {
        return $this->powerA_name;
    }

    public function setPowerAName(?string $powerA_name): static
    {
        $this->powerA_name = $powerA_name;
        return $this;
    }

    public function getPowerBId(): ?string
    {
        return $this->powerB_id;
    }

    public function setPowerBId(?string $powerB_id): static
    {
        $this->powerB_id = $powerB_id;
        return $this;
    }

    public function getPowerBName(): ?string
    {
        return $this->powerB_name;
    }

    public function setPowerBName(?string $powerB_name): static
    {
        $this->powerB_name = $powerB_name;
        return $this;
    }

    public function getFiberinterfacelistList(): array
    {
        return $this->fiberinterfacelist_list;
    }

    public function setFiberinterfacelistList(array $fiberinterfacelist_list): static
    {
        $this->fiberinterfacelist_list = $fiberinterfacelist_list;
        return $this;
    }

    public function getSanList(): array
    {
        return $this->san_list;
    }

    public function setSanList(array $san_list): static
    {
        $this->san_list = $san_list;
        return $this;
    }

    public function getRedundancy(): ?string
    {
        return $this->redundancy;
    }

    public function setRedundancy(?string $redundancy): static
    {
        $this->redundancy = $redundancy;
        return $this;
    }

    public function getPositionV(): ?string
    {
        return $this->position_v;
    }

    public function setPositionV(?string $position_v): static
    {
        $this->position_v = $position_v;
        return $this;
    }

    public function getPositionP(): ?string
    {
        return $this->position_p;
    }

    public function setPositionP(?string $position_p): static
    {
        $this->position_p = $position_p;
        return $this;
    }

    public function getZeroU(): ?string
    {
        return $this->zero_u;
    }

    public function setZeroU(?string $zero_u): static
    {
        $this->zero_u = $zero_u;
        return $this;
    }

    public function getPositionH(): ?string
    {
        return $this->position_h;
    }

    public function setPositionH(?string $position_h): static
    {
        $this->position_h = $position_h;
        return $this;
    }

    public function getNbCols(): ?string
    {
        return $this->nb_cols;
    }

    public function setNbCols(?string $nb_cols): static
    {
        $this->nb_cols = $nb_cols;
        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getExpectedPowerInput(): ?string
    {
        return $this->expected_power_input;
    }

    public function setExpectedPowerInput(?string $expected_power_input): static
    {
        $this->expected_power_input = $expected_power_input;
        return $this;
    }

    public function getManagementipId(): ?string
    {
        return $this->managementip_id;
    }

    public function setManagementipId(?string $managementip_id): static
    {
        $this->managementip_id = $managementip_id;
        return $this;
    }

    public function getManagementipName(): ?string
    {
        return $this->managementip_name;
    }

    public function setManagementipName(?string $managementip_name): static
    {
        $this->managementip_name = $managementip_name;
        return $this;
    }

    public function getNetworkAId(): ?string
    {
        return $this->networkA_id;
    }

    public function setNetworkAId(?string $networkA_id): static
    {
        $this->networkA_id = $networkA_id;
        return $this;
    }

    public function getNetworkAName(): ?string
    {
        return $this->networkA_name;
    }

    public function setNetworkAName(?string $networkA_name): static
    {
        $this->networkA_name = $networkA_name;
        return $this;
    }

    public function getNetworkBId(): ?string
    {
        return $this->networkB_id;
    }

    public function setNetworkBId(?string $networkB_id): static
    {
        $this->networkB_id = $networkB_id;
        return $this;
    }

    public function getNetworkBName(): ?string
    {
        return $this->networkB_name;
    }

    public function setNetworkBName(?string $networkB_name): static
    {
        $this->networkB_name = $networkB_name;
        return $this;
    }

    public function getRedundancyNetwork(): ?string
    {
        return $this->redundancy_network;
    }

    public function setRedundancyNetwork(?string $redundancy_network): static
    {
        $this->redundancy_network = $redundancy_network;
        return $this;
    }

    public function getFiberQuantity(): ?string
    {
        return $this->fiber_quantity;
    }

    public function setFiberQuantity(?string $fiber_quantity): static
    {
        $this->fiber_quantity = $fiber_quantity;
        return $this;
    }

    public function getPdlList(): array
    {
        return $this->pdl_list;
    }

    public function setPdlList(array $pdl_list): static
    {
        $this->pdl_list = $pdl_list;
        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    public function setCustomerId(?string $customer_id): static
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(?string $customer_name): static
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    public function getCustomerCodeclient(): ?string
    {
        return $this->customer_codeclient;
    }

    public function setCustomerCodeclient(?string $customer_codeclient): static
    {
        $this->customer_codeclient = $customer_codeclient;
        return $this;
    }

    public function getRackIdFriendlyname(): ?string
    {
        return $this->rack_id_friendlyname;
    }

    public function setRackIdFriendlyname(?string $rack_id_friendlyname): static
    {
        $this->rack_id_friendlyname = $rack_id_friendlyname;
        return $this;
    }

    public function getRackIdObsolescenceFlag(): ?string
    {
        return $this->rack_id_obsolescence_flag;
    }

    public function setRackIdObsolescenceFlag(?string $rack_id_obsolescence_flag): static
    {
        $this->rack_id_obsolescence_flag = $rack_id_obsolescence_flag;
        return $this;
    }

    public function getEnclosureIdFriendlyname(): ?string
    {
        return $this->enclosure_id_friendlyname;
    }

    public function setEnclosureIdFriendlyname(?string $enclosure_id_friendlyname): static
    {
        $this->enclosure_id_friendlyname = $enclosure_id_friendlyname;
        return $this;
    }

    public function getEnclosureIdObsolescenceFlag(): ?string
    {
        return $this->enclosure_id_obsolescence_flag;
    }

    public function setEnclosureIdObsolescenceFlag(?string $enclosure_id_obsolescence_flag): static
    {
        $this->enclosure_id_obsolescence_flag = $enclosure_id_obsolescence_flag;
        return $this;
    }

    public function getPowerAIdFriendlyname(): ?string
    {
        return $this->powerA_id_friendlyname;
    }

    public function setPowerAIdFriendlyname(?string $powerA_id_friendlyname): static
    {
        $this->powerA_id_friendlyname = $powerA_id_friendlyname;
        return $this;
    }

    public function getPowerAIdFinalclassRecall(): ?string
    {
        return $this->powerA_id_finalclass_recall;
    }

    public function setPowerAIdFinalclassRecall(?string $powerA_id_finalclass_recall): static
    {
        $this->powerA_id_finalclass_recall = $powerA_id_finalclass_recall;
        return $this;
    }

    public function getPowerAIdObsolescenceFlag(): ?string
    {
        return $this->powerA_id_obsolescence_flag;
    }

    public function setPowerAIdObsolescenceFlag(?string $powerA_id_obsolescence_flag): static
    {
        $this->powerA_id_obsolescence_flag = $powerA_id_obsolescence_flag;
        return $this;
    }

    public function getPowerBIdFriendlyname(): ?string
    {
        return $this->powerB_id_friendlyname;
    }

    public function setPowerBIdFriendlyname(?string $powerB_id_friendlyname): static
    {
        $this->powerB_id_friendlyname = $powerB_id_friendlyname;
        return $this;
    }

    public function getPowerBIdFinalclassRecall(): ?string
    {
        return $this->powerB_id_finalclass_recall;
    }

    public function setPowerBIdFinalclassRecall(?string $powerB_id_finalclass_recall): static
    {
        $this->powerB_id_finalclass_recall = $powerB_id_finalclass_recall;
        return $this;
    }

    public function getPowerBIdObsolescenceFlag(): ?string
    {
        return $this->powerB_id_obsolescence_flag;
    }

    public function setPowerBIdObsolescenceFlag(?string $powerB_id_obsolescence_flag): static
    {
        $this->powerB_id_obsolescence_flag = $powerB_id_obsolescence_flag;
        return $this;
    }

    public function getManagementipIdFriendlyname(): ?string
    {
        return $this->managementip_id_friendlyname;
    }

    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): static
    {
        $this->managementip_id_friendlyname = $managementip_id_friendlyname;
        return $this;
    }

    public function getManagementipIdFinalclassRecall(): ?string
    {
        return $this->managementip_id_finalclass_recall;
    }

    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): static
    {
        $this->managementip_id_finalclass_recall = $managementip_id_finalclass_recall;
        return $this;
    }

    public function getNetworkAIdFriendlyname(): ?string
    {
        return $this->networkA_id_friendlyname;
    }

    public function setNetworkAIdFriendlyname(?string $networkA_id_friendlyname): static
    {
        $this->networkA_id_friendlyname = $networkA_id_friendlyname;
        return $this;
    }

    public function getNetworkAIdObsolescenceFlag(): ?string
    {
        return $this->networkA_id_obsolescence_flag;
    }

    public function setNetworkAIdObsolescenceFlag(?string $networkA_id_obsolescence_flag): static
    {
        $this->networkA_id_obsolescence_flag = $networkA_id_obsolescence_flag;
        return $this;
    }

    public function getNetworkBIdFriendlyname(): ?string
    {
        return $this->networkB_id_friendlyname;
    }

    public function setNetworkBIdFriendlyname(?string $networkB_id_friendlyname): static
    {
        $this->networkB_id_friendlyname = $networkB_id_friendlyname;
        return $this;
    }

    public function getNetworkBIdObsolescenceFlag(): ?string
    {
        return $this->networkB_id_obsolescence_flag;
    }

    public function setNetworkBIdObsolescenceFlag(?string $networkB_id_obsolescence_flag): static
    {
        $this->networkB_id_obsolescence_flag = $networkB_id_obsolescence_flag;
        return $this;
    }

    public function getCustomerIdFriendlyname(): ?string
    {
        return $this->customer_id_friendlyname;
    }

    public function setCustomerIdFriendlyname(?string $customer_id_friendlyname): static
    {
        $this->customer_id_friendlyname = $customer_id_friendlyname;
        return $this;
    }

    public function getCustomerIdObsolescenceFlag(): ?string
    {
        return $this->customer_id_obsolescence_flag;
    }

    public function setCustomerIdObsolescenceFlag(?string $customer_id_obsolescence_flag): static
    {
        $this->customer_id_obsolescence_flag = $customer_id_obsolescence_flag;
        return $this;
    }
}