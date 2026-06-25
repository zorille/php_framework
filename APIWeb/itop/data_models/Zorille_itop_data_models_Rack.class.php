<?php

namespace Zorille\itop\data_models;

class Rack extends FunctionalCI
{
    const ENTITY_NAME = 'Rack';

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
    protected ?string $nb_u = null;
    protected array $device_list = [];
    protected ?string $enclosure_list = null;
    protected ?string $units_order = null;
    protected ?string $occupancy_rate_f = null;
    protected ?string $occupancy_rate_r = null;
    protected ?string $weight = null;
    protected ?string $cumulated_weight = null;
    protected ?string $max_allowed_weight = null;
    protected ?string $used_weight_capacity = null;
    protected ?string $cumulated_power_input = null;
    protected ?string $contracted_power = null;
    protected ?string $power_reading_value = null;
    protected ?string $power_reading_date = null;
    protected ?string $power_reading_usage = null;
    protected ?string $datacenterslot_list = null;
    protected ?string $macaddress = null;
    protected ?string $ipaddress_id = null;
    protected ?string $ipaddress_name = null;
    protected ?string $patchpanels_list = null;
    protected ?string $customer_id = null;
    protected ?string $customer_name = null;
    protected ?string $PDU_list = null;
    protected ?string $pod_id = null;
    protected ?string $friendlyname = null;
    protected string  $finalclass = 'Rack';
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $brand_id_friendlyname = null;
    protected ?string $model_id_friendlyname = null;
    protected ?string $ipaddress_id_friendlyname = null;
    protected ?string $ipaddress_id_finalclass_recall = null;
    protected ?string $customer_id_friendlyname = null;
    protected ?string $customer_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    /**
     * @param string|null $serialnumber
     * @return Rack
     */
    public function setSerialnumber(?string $serialnumber): static
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
     * @return Rack
     */
    public function setLocationId(?string $location_id): static
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
     * @return Rack
     */
    public function setLocationName(?string $location_name): static
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
     * @return Rack
     */
    public function setStatus(?string $status): static
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
     * @return Rack
     */
    public function setBrandId(?string $brand_id): static
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
     * @return Rack
     */
    public function setBrandName(?string $brand_name): static
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
     * @return Rack
     */
    public function setModelId(?string $model_id): static
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
     * @return Rack
     */
    public function setModelName(?string $model_name): static
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
     * @return Rack
     */
    public function setAssetNumber(?string $asset_number): static
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
     * @return Rack
     */
    public function setPurchaseDate(?string $purchase_date): static
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
     * @return Rack
     */
    public function setEndOfWarranty(?string $end_of_warranty): static
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
     * @return Rack
     */
    public function setMaintenanceDate(?string $maintenance_date): static
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
     * @return Rack
     */
    public function setMaintenanceFrequency(?string $maintenance_frequency): static
    {
        $this->maintenance_frequency = $maintenance_frequency;
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
     * @return Rack
     */
    public function setNbU(?string $nb_u): static
    {
        $this->nb_u = $nb_u;
        return $this;
    }

    /**
     * @return array
     */
    public function getDeviceList(): array
    {
        return $this->device_list;
    }

    /**
     * @param array $device_list
     * @return Rack
     */
    public function setDeviceList(array $device_list): static
    {
        $this->device_list = $device_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosureList(): ?string
    {
        return $this->enclosure_list;
    }

    /**
     * @param string|null $enclosure_list
     * @return Rack
     */
    public function setEnclosureList(?string $enclosure_list): static
    {
        $this->enclosure_list = $enclosure_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnitsOrder(): ?string
    {
        return $this->units_order;
    }

    /**
     * @param string|null $units_order
     * @return Rack
     */
    public function setUnitsOrder(?string $units_order): static
    {
        $this->units_order = $units_order;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOccupancyRateF(): ?string
    {
        return $this->occupancy_rate_f;
    }

    /**
     * @param string|null $occupancy_rate_f
     * @return Rack
     */
    public function setOccupancyRateF(?string $occupancy_rate_f): static
    {
        $this->occupancy_rate_f = $occupancy_rate_f;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOccupancyRateR(): ?string
    {
        return $this->occupancy_rate_r;
    }

    /**
     * @param string|null $occupancy_rate_r
     * @return Rack
     */
    public function setOccupancyRateR(?string $occupancy_rate_r): static
    {
        $this->occupancy_rate_r = $occupancy_rate_r;
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
     * @return Rack
     */
    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCumulatedWeight(): ?string
    {
        return $this->cumulated_weight;
    }

    /**
     * @param string|null $cumulated_weight
     * @return Rack
     */
    public function setCumulatedWeight(?string $cumulated_weight): static
    {
        $this->cumulated_weight = $cumulated_weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaxAllowedWeight(): ?string
    {
        return $this->max_allowed_weight;
    }

    /**
     * @param string|null $max_allowed_weight
     * @return Rack
     */
    public function setMaxAllowedWeight(?string $max_allowed_weight): static
    {
        $this->max_allowed_weight = $max_allowed_weight;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsedWeightCapacity(): ?string
    {
        return $this->used_weight_capacity;
    }

    /**
     * @param string|null $used_weight_capacity
     * @return Rack
     */
    public function setUsedWeightCapacity(?string $used_weight_capacity): static
    {
        $this->used_weight_capacity = $used_weight_capacity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCumulatedPowerInput(): ?string
    {
        return $this->cumulated_power_input;
    }

    /**
     * @param string|null $cumulated_power_input
     * @return Rack
     */
    public function setCumulatedPowerInput(?string $cumulated_power_input): static
    {
        $this->cumulated_power_input = $cumulated_power_input;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContractedPower(): ?string
    {
        return $this->contracted_power;
    }

    /**
     * @param string|null $contracted_power
     * @return Rack
     */
    public function setContractedPower(?string $contracted_power): static
    {
        $this->contracted_power = $contracted_power;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerReadingValue(): ?string
    {
        return $this->power_reading_value;
    }

    /**
     * @param string|null $power_reading_value
     * @return Rack
     */
    public function setPowerReadingValue(?string $power_reading_value): static
    {
        $this->power_reading_value = $power_reading_value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerReadingDate(): ?string
    {
        return $this->power_reading_date;
    }

    /**
     * @param string|null $power_reading_date
     * @return Rack
     */
    public function setPowerReadingDate(?string $power_reading_date): static
    {
        $this->power_reading_date = $power_reading_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerReadingUsage(): ?string
    {
        return $this->power_reading_usage;
    }

    /**
     * @param string|null $power_reading_usage
     * @return Rack
     */
    public function setPowerReadingUsage(?string $power_reading_usage): static
    {
        $this->power_reading_usage = $power_reading_usage;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDatacenterslotList(): ?string
    {
        return $this->datacenterslot_list;
    }

    /**
     * @param string|null $datacenterslot_list
     * @return Rack
     */
    public function setDatacenterslotList(?string $datacenterslot_list): static
    {
        $this->datacenterslot_list = $datacenterslot_list;
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
     * @return Rack
     */
    public function setMacaddress(?string $macaddress): static
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
     * @return Rack
     */
    public function setIpaddressId(?string $ipaddress_id): static
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
     * @return Rack
     */
    public function setIpaddressName(?string $ipaddress_name): static
    {
        $this->ipaddress_name = $ipaddress_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPatchpanelsList(): ?string
    {
        return $this->patchpanels_list;
    }

    /**
     * @param string|null $patchpanels_list
     * @return Rack
     */
    public function setPatchpanelsList(?string $patchpanels_list): static
    {
        $this->patchpanels_list = $patchpanels_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    /**
     * @param string|null $customer_id
     * @return Rack
     */
    public function setCustomerId(?string $customer_id): static
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    /**
     * @param string|null $customer_name
     * @return Rack
     */
    public function setCustomerName(?string $customer_name): static
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPDUList(): ?string
    {
        return $this->PDU_list;
    }

    /**
     * @param string|null $PDU_list
     * @return Rack
     */
    public function setPDUList(?string $PDU_list): static
    {
        $this->PDU_list = $PDU_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPodId(): ?string
    {
        return $this->pod_id;
    }

    /**
     * @param string|null $pod_id
     * @return Rack
     */
    public function setPodId(?string $pod_id): static
    {
        $this->pod_id = $pod_id;
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
     * @return Rack
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): static
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
     * @return Rack
     */
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): static
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
     * @return Rack
     */
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): static
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
     * @return Rack
     */
    public function setBrandIdFriendlyname(?string $brand_id_friendlyname): static
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
     * @return Rack
     */
    public function setModelIdFriendlyname(?string $model_id_friendlyname): static
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
     * @return Rack
     */
    public function setIpaddressIdFriendlyname(?string $ipaddress_id_friendlyname): static
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
     * @return Rack
     */
    public function setIpaddressIdFinalclassRecall(?string $ipaddress_id_finalclass_recall): static
    {
        $this->ipaddress_id_finalclass_recall = $ipaddress_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerIdFriendlyname(): ?string
    {
        return $this->customer_id_friendlyname;
    }

    /**
     * @param string|null $customer_id_friendlyname
     * @return Rack
     */
    public function setCustomerIdFriendlyname(?string $customer_id_friendlyname): static
    {
        $this->customer_id_friendlyname = $customer_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerIdObsolescenceFlag(): ?string
    {
        return $this->customer_id_obsolescence_flag;
    }

    /**
     * @param string|null $customer_id_obsolescence_flag
     * @return Rack
     */
    public function setCustomerIdObsolescenceFlag(?string $customer_id_obsolescence_flag): static
    {
        $this->customer_id_obsolescence_flag = $customer_id_obsolescence_flag;
        return $this;
    }
}