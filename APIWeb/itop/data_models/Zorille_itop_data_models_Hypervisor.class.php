<?php

namespace Zorille\itop\data_models;

class Hypervisor extends FunctionalCI
{
    protected ?string $overview = null;
    protected ?string $monitoring_list = null;
    protected ?string $status = null;
    protected ?string $logicalvolumes_list = null;
    protected ?string $virtualmachine_list = null;
    protected ?string $farm_id = null;
    protected ?string $farm_name = null;
    protected ?string $server_id = null;
    protected ?string $server_name = null;
    protected ?string $fqdn = null;
    protected ?string $osfamily_id = null;
    protected ?string $osfamily_name = null;
    protected ?string $osversion_id = null;
    protected ?string $osversion_name = null;
    protected ?string $oslicence_id = null;
    protected ?string $oslicence_name = null;
    protected ?string $managementip = null;
    protected ?string $vswitchs_list = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $farm_id_friendlyname = null;
    protected ?string $farm_id_obsolescence_flag = null;
    protected ?string $server_id_friendlyname = null;
    protected ?string $server_id_obsolescence_flag = null;
    protected ?string $osfamily_id_friendlyname = null;
    protected ?string $osversion_id_friendlyname = null;
    protected ?string $oslicence_id_friendlyname = null;
    protected ?string $oslicence_id_obsolescence_flag = null;
    protected string $finalclass = 'Hypervisor';

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): Hypervisor
    {
        $this->overview = $overview;
        return $this;
    }

    public function getMonitoringList(): ?string
    {
        return $this->monitoring_list;
    }

    public function setMonitoringList(?string $monitoring_list): Hypervisor
    {
        $this->monitoring_list = $monitoring_list;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): Hypervisor
    {
        $this->status = $status;
        return $this;
    }

    public function getLogicalvolumesList(): ?string
    {
        return $this->logicalvolumes_list;
    }

    public function setLogicalvolumesList(?string $logicalvolumes_list): Hypervisor
    {
        $this->logicalvolumes_list = $logicalvolumes_list;
        return $this;
    }

    public function getVirtualmachineList(): ?string
    {
        return $this->virtualmachine_list;
    }

    public function setVirtualmachineList(?string $virtualmachine_list): Hypervisor
    {
        $this->virtualmachine_list = $virtualmachine_list;
        return $this;
    }

    public function getFarmId(): ?string
    {
        return $this->farm_id;
    }

    public function setFarmId(?string $farm_id): Hypervisor
    {
        $this->farm_id = $farm_id;
        return $this;
    }

    public function getFarmName(): ?string
    {
        return $this->farm_name;
    }

    public function setFarmName(?string $farm_name): Hypervisor
    {
        $this->farm_name = $farm_name;
        return $this;
    }

    public function getServerId(): ?string
    {
        return $this->server_id;
    }

    public function setServerId(?string $server_id): Hypervisor
    {
        $this->server_id = $server_id;
        return $this;
    }

    public function getServerName(): ?string
    {
        return $this->server_name;
    }

    public function setServerName(?string $server_name): Hypervisor
    {
        $this->server_name = $server_name;
        return $this;
    }

    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    public function setFqdn(?string $fqdn): Hypervisor
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    public function getOsfamilyId(): ?string
    {
        return $this->osfamily_id;
    }

    public function setOsfamilyId(?string $osfamily_id): Hypervisor
    {
        $this->osfamily_id = $osfamily_id;
        return $this;
    }

    public function getOsfamilyName(): ?string
    {
        return $this->osfamily_name;
    }

    public function setOsfamilyName(?string $osfamily_name): Hypervisor
    {
        $this->osfamily_name = $osfamily_name;
        return $this;
    }

    public function getOsversionId(): ?string
    {
        return $this->osversion_id;
    }

    public function setOsversionId(?string $osversion_id): Hypervisor
    {
        $this->osversion_id = $osversion_id;
        return $this;
    }

    public function getOsversionName(): ?string
    {
        return $this->osversion_name;
    }

    public function setOsversionName(?string $osversion_name): Hypervisor
    {
        $this->osversion_name = $osversion_name;
        return $this;
    }

    public function getOslicenceId(): ?string
    {
        return $this->oslicence_id;
    }

    public function setOslicenceId(?string $oslicence_id): Hypervisor
    {
        $this->oslicence_id = $oslicence_id;
        return $this;
    }

    public function getOslicenceName(): ?string
    {
        return $this->oslicence_name;
    }

    public function setOslicenceName(?string $oslicence_name): Hypervisor
    {
        $this->oslicence_name = $oslicence_name;
        return $this;
    }

    public function getManagementip(): ?string
    {
        return $this->managementip;
    }

    public function setManagementip(?string $managementip): Hypervisor
    {
        $this->managementip = $managementip;
        return $this;
    }

    public function getVswitchsList(): ?string
    {
        return $this->vswitchs_list;
    }

    public function setVswitchsList(?string $vswitchs_list): Hypervisor
    {
        $this->vswitchs_list = $vswitchs_list;
        return $this;
    }

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }

    public function setObsolescenceFlag(?string $obsolescence_flag): Hypervisor
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getFarmIdFriendlyname(): ?string
    {
        return $this->farm_id_friendlyname;
    }

    public function setFarmIdFriendlyname(?string $farm_id_friendlyname): Hypervisor
    {
        $this->farm_id_friendlyname = $farm_id_friendlyname;
        return $this;
    }

    public function getFarmIdObsolescenceFlag(): ?string
    {
        return $this->farm_id_obsolescence_flag;
    }

    public function setFarmIdObsolescenceFlag(?string $farm_id_obsolescence_flag): Hypervisor
    {
        $this->farm_id_obsolescence_flag = $farm_id_obsolescence_flag;
        return $this;
    }

    public function getServerIdFriendlyname(): ?string
    {
        return $this->server_id_friendlyname;
    }

    public function setServerIdFriendlyname(?string $server_id_friendlyname): Hypervisor
    {
        $this->server_id_friendlyname = $server_id_friendlyname;
        return $this;
    }

    public function getServerIdObsolescenceFlag(): ?string
    {
        return $this->server_id_obsolescence_flag;
    }

    public function setServerIdObsolescenceFlag(?string $server_id_obsolescence_flag): Hypervisor
    {
        $this->server_id_obsolescence_flag = $server_id_obsolescence_flag;
        return $this;
    }

    public function getOsfamilyIdFriendlyname(): ?string
    {
        return $this->osfamily_id_friendlyname;
    }

    public function setOsfamilyIdFriendlyname(?string $osfamily_id_friendlyname): Hypervisor
    {
        $this->osfamily_id_friendlyname = $osfamily_id_friendlyname;
        return $this;
    }

    public function getOsversionIdFriendlyname(): ?string
    {
        return $this->osversion_id_friendlyname;
    }

    public function setOsversionIdFriendlyname(?string $osversion_id_friendlyname): Hypervisor
    {
        $this->osversion_id_friendlyname = $osversion_id_friendlyname;
        return $this;
    }

    public function getOslicenceIdFriendlyname(): ?string
    {
        return $this->oslicence_id_friendlyname;
    }

    public function setOslicenceIdFriendlyname(?string $oslicence_id_friendlyname): Hypervisor
    {
        $this->oslicence_id_friendlyname = $oslicence_id_friendlyname;
        return $this;
    }

    public function getOslicenceIdObsolescenceFlag(): ?string
    {
        return $this->oslicence_id_obsolescence_flag;
    }

    public function setOslicenceIdObsolescenceFlag(?string $oslicence_id_obsolescence_flag): Hypervisor
    {
        $this->oslicence_id_obsolescence_flag = $oslicence_id_obsolescence_flag;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): Hypervisor
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}