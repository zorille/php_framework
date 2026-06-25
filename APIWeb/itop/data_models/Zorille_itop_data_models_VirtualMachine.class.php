<?php

namespace Zorille\itop\data_models;

class VirtualMachine extends FunctionalCI
{
    const ENTITY_NAME = 'VirtualMachine';

    protected ?string $status = null;
    protected ?string $logicalvolumes_list = null;
    protected ?string $virtualhost_id = null;
    protected ?string $virtualhost_name = null;
    protected ?string $osfamily_id = null;
    protected ?string $osfamily_name = null;
    protected ?string $osversion_id = null;
    protected ?string $osversion_name = null;
    protected ?string $oslicence_id = null;
    protected ?string $oslicence_name = null;
    protected ?string $cpu = null;
    protected ?string $ram = null;
    protected ?string $logicalinterface_list = null;
    protected ?string $ocs_oscomment = null;
    protected ?string $ocs_id = null;
    protected ?string $cvss = null;
    protected ?string $managementip_id = null;
    protected ?string $managementip_name = null;
    protected ?string $fqdn = null;
    protected ?string $vmtemplate_id = null;
    protected ?string $vmtemplate_name = null;
    protected ?string $vmvirtualdisk_list = null;
    protected ?string $powerstate = null;
    protected ?string $hostname = null;
    protected ?string $backup = null;
    protected ?string $backup_name = null;
    protected ?string $praaas = null;
    protected ?string $praaas_name = null;
    protected ?string $friendlyname = null;
    protected string $finalclass = 'VirtualMachine';
    protected ?string $obsolescence_flag = null;
    protected ?string $virtualhost_id_friendlyname = null;
    protected ?string $virtualhost_id_finalclass_recall = null;
    protected ?string $virtualhost_id_obsolescence_flag = null;
    protected ?string $osfamily_id_friendlyname = null;
    protected ?string $osversion_id_friendlyname = null;
    protected ?string $oslicence_id_friendlyname = null;
    protected ?string $oslicence_id_obsolescence_flag = null;
    protected ?string $managementip_id_friendlyname = null;
    protected ?string $managementip_id_finalclass_recall = null;
    protected ?string $vmtemplate_id_friendlyname = null;
    protected ?string $vmtemplate_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return VirtualMachine
     */
    public function setStatus(?string $status): VirtualMachine
    {
        $this->status = $status;
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
     * @return VirtualMachine
     */
    public function setLogicalvolumesList(?string $logicalvolumes_list): VirtualMachine
    {
        $this->logicalvolumes_list = $logicalvolumes_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVirtualhostId(): ?string
    {
        return $this->virtualhost_id;
    }

    /**
     * @param string|null $virtualhost_id
     * @return VirtualMachine
     */
    public function setVirtualhostId(?string $virtualhost_id): VirtualMachine
    {
        $this->virtualhost_id = $virtualhost_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVirtualhostName(): ?string
    {
        return $this->virtualhost_name;
    }

    /**
     * @param string|null $virtualhost_name
     * @return VirtualMachine
     */
    public function setVirtualhostName(?string $virtualhost_name): VirtualMachine
    {
        $this->virtualhost_name = $virtualhost_name;
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
     * @return VirtualMachine
     */
    public function setOsfamilyId(?string $osfamily_id): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOsfamilyName(?string $osfamily_name): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOsversionId(?string $osversion_id): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOsversionName(?string $osversion_name): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOslicenceId(?string $oslicence_id): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOslicenceName(?string $oslicence_name): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setCpu(?string $cpu): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setRam(?string $ram): VirtualMachine
    {
        $this->ram = $ram;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogicalinterfaceList(): ?string
    {
        return $this->logicalinterface_list;
    }

    /**
     * @param string|null $logicalinterface_list
     * @return VirtualMachine
     */
    public function setLogicalinterfaceList(?string $logicalinterface_list): VirtualMachine
    {
        $this->logicalinterface_list = $logicalinterface_list;
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
     * @return VirtualMachine
     */
    public function setOcsOscomment(?string $ocs_oscomment): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOcsId(?string $ocs_id): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setCvss(?string $cvss): VirtualMachine
    {
        $this->cvss = $cvss;
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
     * @return VirtualMachine
     */
    public function setManagementipId(?string $managementip_id): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setManagementipName(?string $managementip_name): VirtualMachine
    {
        $this->managementip_name = $managementip_name;
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
     * @return VirtualMachine
     */
    public function setFqdn(?string $fqdn): VirtualMachine
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVmtemplateId(): ?string
    {
        return $this->vmtemplate_id;
    }

    /**
     * @param string|null $vmtemplate_id
     * @return VirtualMachine
     */
    public function setVmtemplateId(?string $vmtemplate_id): VirtualMachine
    {
        $this->vmtemplate_id = $vmtemplate_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVmtemplateName(): ?string
    {
        return $this->vmtemplate_name;
    }

    /**
     * @param string|null $vmtemplate_name
     * @return VirtualMachine
     */
    public function setVmtemplateName(?string $vmtemplate_name): VirtualMachine
    {
        $this->vmtemplate_name = $vmtemplate_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVmvirtualdiskList(): ?string
    {
        return $this->vmvirtualdisk_list;
    }

    /**
     * @param string|null $vmvirtualdisk_list
     * @return VirtualMachine
     */
    public function setVmvirtualdiskList(?string $vmvirtualdisk_list): VirtualMachine
    {
        $this->vmvirtualdisk_list = $vmvirtualdisk_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPowerstate(): ?string
    {
        return $this->powerstate;
    }

    /**
     * @param string|null $powerstate
     * @return VirtualMachine
     */
    public function setPowerstate(?string $powerstate): VirtualMachine
    {
        $this->powerstate = $powerstate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    /**
     * @param string|null $hostname
     * @return VirtualMachine
     */
    public function setHostname(?string $hostname): VirtualMachine
    {
        $this->hostname = $hostname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackup(): ?string
    {
        return $this->backup;
    }

    /**
     * @param string|null $backup
     * @return VirtualMachine
     */
    public function setBackup(?string $backup): VirtualMachine
    {
        $this->backup = $backup;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackupName(): ?string
    {
        return $this->backup_name;
    }

    /**
     * @param string|null $backup_name
     * @return VirtualMachine
     */
    public function setBackupName(?string $backup_name): VirtualMachine
    {
        $this->backup_name = $backup_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPraaas(): ?string
    {
        return $this->praaas;
    }

    /**
     * @param string|null $praaas
     * @return VirtualMachine
     */
    public function setPraaas(?string $praaas): VirtualMachine
    {
        $this->praaas = $praaas;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPraaasName(): ?string
    {
        return $this->praaas_name;
    }

    /**
     * @param string|null $praaas_name
     * @return VirtualMachine
     */
    public function setPraaasName(?string $praaas_name): VirtualMachine
    {
        $this->praaas_name = $praaas_name;
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
     * @return VirtualMachine
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): VirtualMachine
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVirtualhostIdFriendlyname(): ?string
    {
        return $this->virtualhost_id_friendlyname;
    }

    /**
     * @param string|null $virtualhost_id_friendlyname
     * @return VirtualMachine
     */
    public function setVirtualhostIdFriendlyname(?string $virtualhost_id_friendlyname): VirtualMachine
    {
        $this->virtualhost_id_friendlyname = $virtualhost_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVirtualhostIdFinalclassRecall(): ?string
    {
        return $this->virtualhost_id_finalclass_recall;
    }

    /**
     * @param string|null $virtualhost_id_finalclass_recall
     * @return VirtualMachine
     */
    public function setVirtualhostIdFinalclassRecall(?string $virtualhost_id_finalclass_recall): VirtualMachine
    {
        $this->virtualhost_id_finalclass_recall = $virtualhost_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVirtualhostIdObsolescenceFlag(): ?string
    {
        return $this->virtualhost_id_obsolescence_flag;
    }

    /**
     * @param string|null $virtualhost_id_obsolescence_flag
     * @return VirtualMachine
     */
    public function setVirtualhostIdObsolescenceFlag(?string $virtualhost_id_obsolescence_flag): VirtualMachine
    {
        $this->virtualhost_id_obsolescence_flag = $virtualhost_id_obsolescence_flag;
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
     * @return VirtualMachine
     */
    public function setOsfamilyIdFriendlyname(?string $osfamily_id_friendlyname): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOsversionIdFriendlyname(?string $osversion_id_friendlyname): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOslicenceIdFriendlyname(?string $oslicence_id_friendlyname): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setOslicenceIdObsolescenceFlag(?string $oslicence_id_obsolescence_flag): VirtualMachine
    {
        $this->oslicence_id_obsolescence_flag = $oslicence_id_obsolescence_flag;
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
     * @return VirtualMachine
     */
    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): VirtualMachine
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
     * @return VirtualMachine
     */
    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): VirtualMachine
    {
        $this->managementip_id_finalclass_recall = $managementip_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVmtemplateIdFriendlyname(): ?string
    {
        return $this->vmtemplate_id_friendlyname;
    }

    /**
     * @param string|null $vmtemplate_id_friendlyname
     * @return VirtualMachine
     */
    public function setVmtemplateIdFriendlyname(?string $vmtemplate_id_friendlyname): VirtualMachine
    {
        $this->vmtemplate_id_friendlyname = $vmtemplate_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVmtemplateIdObsolescenceFlag(): ?string
    {
        return $this->vmtemplate_id_obsolescence_flag;
    }

    /**
     * @param string|null $vmtemplate_id_obsolescence_flag
     * @return VirtualMachine
     */
    public function setVmtemplateIdObsolescenceFlag(?string $vmtemplate_id_obsolescence_flag): VirtualMachine
    {
        $this->vmtemplate_id_obsolescence_flag = $vmtemplate_id_obsolescence_flag;
        return $this;
    }
}