<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_models\FunctionalCI;

class VMTemplate extends FunctionalCI
{
    protected ?string $overview = null;
    protected ?string $monitoring_list = null;
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
    protected string $finalclass = 'VMTemplate';

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): VMTemplate
    {
        $this->overview = $overview;
        return $this;
    }

    public function getMonitoringList(): ?string
    {
        return $this->monitoring_list;
    }

    public function setMonitoringList(?string $monitoring_list): VMTemplate
    {
        $this->monitoring_list = $monitoring_list;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): VMTemplate
    {
        $this->status = $status;
        return $this;
    }

    public function getLogicalvolumesList(): ?string
    {
        return $this->logicalvolumes_list;
    }

    public function setLogicalvolumesList(?string $logicalvolumes_list): VMTemplate
    {
        $this->logicalvolumes_list = $logicalvolumes_list;
        return $this;
    }

    public function getVirtualhostId(): ?string
    {
        return $this->virtualhost_id;
    }

    public function setVirtualhostId(?string $virtualhost_id): VMTemplate
    {
        $this->virtualhost_id = $virtualhost_id;
        return $this;
    }

    public function getVirtualhostName(): ?string
    {
        return $this->virtualhost_name;
    }

    public function setVirtualhostName(?string $virtualhost_name): VMTemplate
    {
        $this->virtualhost_name = $virtualhost_name;
        return $this;
    }

    public function getOsfamilyId(): ?string
    {
        return $this->osfamily_id;
    }

    public function setOsfamilyId(?string $osfamily_id): VMTemplate
    {
        $this->osfamily_id = $osfamily_id;
        return $this;
    }

    public function getOsfamilyName(): ?string
    {
        return $this->osfamily_name;
    }

    public function setOsfamilyName(?string $osfamily_name): VMTemplate
    {
        $this->osfamily_name = $osfamily_name;
        return $this;
    }

    public function getOsversionId(): ?string
    {
        return $this->osversion_id;
    }

    public function setOsversionId(?string $osversion_id): VMTemplate
    {
        $this->osversion_id = $osversion_id;
        return $this;
    }

    public function getOsversionName(): ?string
    {
        return $this->osversion_name;
    }

    public function setOsversionName(?string $osversion_name): VMTemplate
    {
        $this->osversion_name = $osversion_name;
        return $this;
    }

    public function getOslicenceId(): ?string
    {
        return $this->oslicence_id;
    }

    public function setOslicenceId(?string $oslicence_id): VMTemplate
    {
        $this->oslicence_id = $oslicence_id;
        return $this;
    }

    public function getOslicenceName(): ?string
    {
        return $this->oslicence_name;
    }

    public function setOslicenceName(?string $oslicence_name): VMTemplate
    {
        $this->oslicence_name = $oslicence_name;
        return $this;
    }

    public function getCpu(): ?string
    {
        return $this->cpu;
    }

    public function setCpu(?string $cpu): VMTemplate
    {
        $this->cpu = $cpu;
        return $this;
    }

    public function getRam(): ?string
    {
        return $this->ram;
    }

    public function setRam(?string $ram): VMTemplate
    {
        $this->ram = $ram;
        return $this;
    }

    public function getLogicalinterfaceList(): ?string
    {
        return $this->logicalinterface_list;
    }

    public function setLogicalinterfaceList(?string $logicalinterface_list): VMTemplate
    {
        $this->logicalinterface_list = $logicalinterface_list;
        return $this;
    }

    public function getOcsOscomment(): ?string
    {
        return $this->ocs_oscomment;
    }

    public function setOcsOscomment(?string $ocs_oscomment): VMTemplate
    {
        $this->ocs_oscomment = $ocs_oscomment;
        return $this;
    }

    public function getOcsId(): ?string
    {
        return $this->ocs_id;
    }

    public function setOcsId(?string $ocs_id): VMTemplate
    {
        $this->ocs_id = $ocs_id;
        return $this;
    }

    public function getCvss(): ?string
    {
        return $this->cvss;
    }

    public function setCvss(?string $cvss): VMTemplate
    {
        $this->cvss = $cvss;
        return $this;
    }

    public function getManagementipId(): ?string
    {
        return $this->managementip_id;
    }

    public function setManagementipId(?string $managementip_id): VMTemplate
    {
        $this->managementip_id = $managementip_id;
        return $this;
    }

    public function getManagementipName(): ?string
    {
        return $this->managementip_name;
    }

    public function setManagementipName(?string $managementip_name): VMTemplate
    {
        $this->managementip_name = $managementip_name;
        return $this;
    }

    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    public function setFqdn(?string $fqdn): VMTemplate
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    public function getVmtemplateId(): ?string
    {
        return $this->vmtemplate_id;
    }

    public function setVmtemplateId(?string $vmtemplate_id): VMTemplate
    {
        $this->vmtemplate_id = $vmtemplate_id;
        return $this;
    }

    public function getVmtemplateName(): ?string
    {
        return $this->vmtemplate_name;
    }

    public function setVmtemplateName(?string $vmtemplate_name): VMTemplate
    {
        $this->vmtemplate_name = $vmtemplate_name;
        return $this;
    }

    public function getVmvirtualdiskList(): ?string
    {
        return $this->vmvirtualdisk_list;
    }

    public function setVmvirtualdiskList(?string $vmvirtualdisk_list): VMTemplate
    {
        $this->vmvirtualdisk_list = $vmvirtualdisk_list;
        return $this;
    }

    public function getPowerstate(): ?string
    {
        return $this->powerstate;
    }

    public function setPowerstate(?string $powerstate): VMTemplate
    {
        $this->powerstate = $powerstate;
        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): VMTemplate
    {
        $this->hostname = $hostname;
        return $this;
    }

    public function getBackup(): ?string
    {
        return $this->backup;
    }

    public function setBackup(?string $backup): VMTemplate
    {
        $this->backup = $backup;
        return $this;
    }

    public function getBackupName(): ?string
    {
        return $this->backup_name;
    }

    public function setBackupName(?string $backup_name): VMTemplate
    {
        $this->backup_name = $backup_name;
        return $this;
    }

    public function getPraaas(): ?string
    {
        return $this->praaas;
    }

    public function setPraaas(?string $praaas): VMTemplate
    {
        $this->praaas = $praaas;
        return $this;
    }

    public function getPraaasName(): ?string
    {
        return $this->praaas_name;
    }

    public function setPraaasName(?string $praaas_name): VMTemplate
    {
        $this->praaas_name = $praaas_name;
        return $this;
    }

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }

    public function setObsolescenceFlag(?string $obsolescence_flag): VMTemplate
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getVirtualhostIdFriendlyname(): ?string
    {
        return $this->virtualhost_id_friendlyname;
    }

    public function setVirtualhostIdFriendlyname(?string $virtualhost_id_friendlyname): VMTemplate
    {
        $this->virtualhost_id_friendlyname = $virtualhost_id_friendlyname;
        return $this;
    }

    public function getVirtualhostIdFinalclassRecall(): ?string
    {
        return $this->virtualhost_id_finalclass_recall;
    }

    public function setVirtualhostIdFinalclassRecall(?string $virtualhost_id_finalclass_recall): VMTemplate
    {
        $this->virtualhost_id_finalclass_recall = $virtualhost_id_finalclass_recall;
        return $this;
    }

    public function getVirtualhostIdObsolescenceFlag(): ?string
    {
        return $this->virtualhost_id_obsolescence_flag;
    }

    public function setVirtualhostIdObsolescenceFlag(?string $virtualhost_id_obsolescence_flag): VMTemplate
    {
        $this->virtualhost_id_obsolescence_flag = $virtualhost_id_obsolescence_flag;
        return $this;
    }

    public function getOsfamilyIdFriendlyname(): ?string
    {
        return $this->osfamily_id_friendlyname;
    }

    public function setOsfamilyIdFriendlyname(?string $osfamily_id_friendlyname): VMTemplate
    {
        $this->osfamily_id_friendlyname = $osfamily_id_friendlyname;
        return $this;
    }

    public function getOsversionIdFriendlyname(): ?string
    {
        return $this->osversion_id_friendlyname;
    }

    public function setOsversionIdFriendlyname(?string $osversion_id_friendlyname): VMTemplate
    {
        $this->osversion_id_friendlyname = $osversion_id_friendlyname;
        return $this;
    }

    public function getOslicenceIdFriendlyname(): ?string
    {
        return $this->oslicence_id_friendlyname;
    }

    public function setOslicenceIdFriendlyname(?string $oslicence_id_friendlyname): VMTemplate
    {
        $this->oslicence_id_friendlyname = $oslicence_id_friendlyname;
        return $this;
    }

    public function getOslicenceIdObsolescenceFlag(): ?string
    {
        return $this->oslicence_id_obsolescence_flag;
    }

    public function setOslicenceIdObsolescenceFlag(?string $oslicence_id_obsolescence_flag): VMTemplate
    {
        $this->oslicence_id_obsolescence_flag = $oslicence_id_obsolescence_flag;
        return $this;
    }

    public function getManagementipIdFriendlyname(): ?string
    {
        return $this->managementip_id_friendlyname;
    }

    public function setManagementipIdFriendlyname(?string $managementip_id_friendlyname): VMTemplate
    {
        $this->managementip_id_friendlyname = $managementip_id_friendlyname;
        return $this;
    }

    public function getManagementipIdFinalclassRecall(): ?string
    {
        return $this->managementip_id_finalclass_recall;
    }

    public function setManagementipIdFinalclassRecall(?string $managementip_id_finalclass_recall): VMTemplate
    {
        $this->managementip_id_finalclass_recall = $managementip_id_finalclass_recall;
        return $this;
    }

    public function getVmtemplateIdFriendlyname(): ?string
    {
        return $this->vmtemplate_id_friendlyname;
    }

    public function setVmtemplateIdFriendlyname(?string $vmtemplate_id_friendlyname): VMTemplate
    {
        $this->vmtemplate_id_friendlyname = $vmtemplate_id_friendlyname;
        return $this;
    }

    public function getVmtemplateIdObsolescenceFlag(): ?string
    {
        return $this->vmtemplate_id_obsolescence_flag;
    }

    public function setVmtemplateIdObsolescenceFlag(?string $vmtemplate_id_obsolescence_flag): VMTemplate
    {
        $this->vmtemplate_id_obsolescence_flag = $vmtemplate_id_obsolescence_flag;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): VMTemplate
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}