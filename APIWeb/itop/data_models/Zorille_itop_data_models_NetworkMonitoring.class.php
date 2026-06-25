<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

/**
 * @method self convert(array $input)
 */
class NetworkMonitoring extends data_model
{
    const ENTITY_NAME = 'NetworkMonitoring';

    protected ?string $name = null;
    protected ?string $org_id = null;
    protected ?string $organization_name = null;
    protected ?string $organization_euclyde_id = null;
    protected ?string $business_criticity = null;
    protected ?StatusEnum $status = null;
    protected ?string $functionalci_id = null;
    protected ?string $functionalci_name = null;
    protected ?string $modele_id = null;
    protected ?string $modele_name = null;
    protected ?string $monitoring_id = null;
    protected ?string $monitoring_url = null;
    protected ?string $etiquettes = null;
    protected ?string $monitoring_box_id = null;
    protected ?string $monitoring_box_name = null;
    protected ?string $controle_id = null;
    protected ?string $controle_name = null;
    protected ?string $seuil_control_alerte = null;
    protected ?string $seuil_control_critique = null;
    protected ?string $category = null;
    protected ?string $monitoring_host_standard_account = null;
    protected ?string $monitoring_host_snmp = null;
    protected ?string $monitoring_host_ssh = null;
    protected ?string $appsmonitoring_list = null;
    protected ?string $network_monitoring_tools = null;
    protected ?string $finalclass = null;
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $obsolescence_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $functionalci_id_friendlyname = null;
    protected ?string $functionalci_id_finalclass_recall = null;
    protected ?string $functionalci_id_obsolescence_flag = null;
    protected ?string $modele_id_friendlyname = null;
    protected ?string $monitoring_box_id_friendlyname = null;
    protected ?string $monitoring_box_id_finalclass_recall = null;
    protected ?string $monitoring_box_id_obsolescence_flag = null;
    protected ?string $controle_id_friendlyname = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * @param string|null $name
     * @return NetworkMonitoring
     */
    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrgId(): ?string
    {
        return $this->org_id;
    }
    /**
     * @param string|null $org_id
     * @return NetworkMonitoring
     */
    public function setOrgId(?string $org_id): static
    {
        $this->org_id = $org_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganizationName(): ?string
    {
        return $this->organization_name;
    }
    /**
     * @param string|null $organization_name
     * @return NetworkMonitoring
     */
    public function setOrganizationName(?string $organization_name): static
    {
        $this->organization_name = $organization_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganizationEuclydeId(): ?string
    {
        return $this->organization_euclyde_id;
    }
    /**
     * @param string|null $organization_euclyde_id
     * @return NetworkMonitoring
     */
    public function setOrganizationEuclydeId(?string $organization_euclyde_id): static
    {
        $this->organization_euclyde_id = $organization_euclyde_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBusinessCriticity(): ?string
    {
        return $this->business_criticity;
    }
    /**
     * @param string|null $business_criticity
     * @return NetworkMonitoring
     */
    public function setBusinessCriticity(?string $business_criticity): static
    {
        $this->business_criticity = $business_criticity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }

    /**
     * @param string|StatusEnum|null $status
     * @return NetworkMonitoring
     */
    public function setStatus(string|StatusEnum|null $status): static
    {
        if (is_string($status)) {
            $status = StatusEnum::from($status);
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalciId(): ?string
    {
        return $this->functionalci_id;
    }
    /**
     * @param string|null $functionalci_id
     * @return NetworkMonitoring
     */
    public function setFunctionalciId(?string $functionalci_id): static
    {
        $this->functionalci_id = $functionalci_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalciName(): ?string
    {
        return $this->functionalci_name;
    }
    /**
     * @param string|null $functionalci_name
     * @return NetworkMonitoring
     */
    public function setFunctionalciName(?string $functionalci_name): static
    {
        $this->functionalci_name = $functionalci_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleId(): ?string
    {
        return $this->modele_id;
    }
    /**
     * @param string|null $modele_id
     * @return NetworkMonitoring
     */
    public function setModeleId(?string $modele_id): static
    {
        $this->modele_id = $modele_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleName(): ?string
    {
        return $this->modele_name;
    }
    /**
     * @param string|null $modele_name
     * @return NetworkMonitoring
     */
    public function setModeleName(?string $modele_name): static
    {
        $this->modele_name = $modele_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringId(): ?string
    {
        return $this->monitoring_id;
    }
    /**
     * @param string|null $monitoring_id
     * @return NetworkMonitoring
     */
    public function setMonitoringId(?string $monitoring_id): static
    {
        $this->monitoring_id = $monitoring_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringUrl(): ?string
    {
        return $this->monitoring_url;
    }
    /**
     * @param string|null $monitoring_url
     * @return NetworkMonitoring
     */
    public function setMonitoringUrl(?string $monitoring_url): static
    {
        $this->monitoring_url = $monitoring_url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEtiquettes(): ?string
    {
        return $this->etiquettes;
    }
    /**
     * @param string|null $etiquettes
     * @return NetworkMonitoring
     */
    public function setEtiquettes(?string $etiquettes): static
    {
        $this->etiquettes = $etiquettes;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringBoxId(): ?string
    {
        return $this->monitoring_box_id;
    }
    /**
     * @param string|null $monitoring_box_id
     * @return NetworkMonitoring
     */
    public function setMonitoringBoxId(?string $monitoring_box_id): static
    {
        $this->monitoring_box_id = $monitoring_box_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringBoxName(): ?string
    {
        return $this->monitoring_box_name;
    }
    /**
     * @param string|null $monitoring_box_name
     * @return NetworkMonitoring
     */
    public function setMonitoringBoxName(?string $monitoring_box_name): static
    {
        $this->monitoring_box_name = $monitoring_box_name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getControleId(): ?string
    {
        return $this->controle_id;
    }
    /**
     * @param string|null $controle_id
     * @return NetworkMonitoring
     */
    public function setControleId(?string $controle_id): static
    {
        $this->controle_id = $controle_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getControleName(): ?string
    {
        return $this->controle_name;
    }
    /**
     * @param string|null $controle_name
     * @return NetworkMonitoring
     */
    public function setControleName(?string $controle_name): static
    {
        $this->controle_name = $controle_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeuilControlAlerte(): ?string
    {
        return $this->seuil_control_alerte;
    }
    /**
     * @param string|null $seuil_control_alerte
     * @return NetworkMonitoring
     */
    public function setSeuilControlAlerte(?string $seuil_control_alerte): static
    {
        $this->seuil_control_alerte = $seuil_control_alerte;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeuilControlCritique(): ?string
    {
        return $this->seuil_control_critique;
    }
    /**
     * @param string|null $seuil_control_critique
     * @return NetworkMonitoring
     */
    public function setSeuilControlCritique(?string $seuil_control_critique): static
    {
        $this->seuil_control_critique = $seuil_control_critique;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }
    /**
     * @param string|null $category
     * @return NetworkMonitoring
     */
    public function setCategory(?string $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostStandardAccount(): ?string
    {
        return $this->monitoring_host_standard_account;
    }
    /**
     * @param string|null $monitoring_host_standard_account
     * @return NetworkMonitoring
     */
    public function setMonitoringHostStandardAccount(?string $monitoring_host_standard_account): static
    {
        $this->monitoring_host_standard_account = $monitoring_host_standard_account;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostSnmp(): ?string
    {
        return $this->monitoring_host_snmp;
    }
    /**
     * @param string|null $monitoring_host_snmp
     * @return NetworkMonitoring
     */
    public function setMonitoringHostSnmp(?string $monitoring_host_snmp): static
    {
        $this->monitoring_host_snmp = $monitoring_host_snmp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostSsh(): ?string
    {
        return $this->monitoring_host_ssh;
    }
    /**
     * @param string|null $monitoring_host_ssh
     * @return NetworkMonitoring
     */
    public function setMonitoringHostSsh(?string $monitoring_host_ssh): static
    {
        $this->monitoring_host_ssh = $monitoring_host_ssh;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAppsmonitoringList(): ?string
    {
        return $this->appsmonitoring_list;
    }
    /**
     * @param string|null $appsmonitoring_list
     * @return NetworkMonitoring
     */
    public function setAppsmonitoringList(?string $appsmonitoring_list): static
    {
        $this->appsmonitoring_list = $appsmonitoring_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetworkMonitoringTools(): ?string
    {
        return $this->network_monitoring_tools;
    }
    /**
     * @param string|null $network_monitoring_tools
     * @return NetworkMonitoring
     */
    public function setNetworkMonitoringTools(?string $network_monitoring_tools): static
    {
        $this->network_monitoring_tools = $network_monitoring_tools;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFinalclass(): ?string
    {
        return $this->finalclass;
    }
    /**
     * @param string|null $finalclass
     * @return NetworkMonitoring
     */
    public function setFinalclass(?string $finalclass): static
    {
        $this->finalclass = $finalclass;
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
     * @return NetworkMonitoring
     */
    public function setFriendlyname(?string $friendlyname): static
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
     * @return NetworkMonitoring
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): static
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObsolescenceDate(): ?string
    {
        return $this->obsolescence_date;
    }
    /**
     * @param string|null $obsolescence_date
     * @return NetworkMonitoring
     */
    public function setObsolescenceDate(?string $obsolescence_date): static
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }
    /**
     * @param string|null $org_id_friendlyname
     * @return NetworkMonitoring
     */
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): static
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }
    /**
     * @param string|null $org_id_obsolescence_flag
     * @return NetworkMonitoring
     */
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): static
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalciIdFriendlyname(): ?string
    {
        return $this->functionalci_id_friendlyname;
    }
    /**
     * @param string|null $functionalci_id_friendlyname
     * @return NetworkMonitoring
     */
    public function setFunctionalciIdFriendlyname(?string $functionalci_id_friendlyname): static
    {
        $this->functionalci_id_friendlyname = $functionalci_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalciIdFinalclassRecall(): ?string
    {
        return $this->functionalci_id_finalclass_recall;
    }
    /**
     * @param string|null $functionalci_id_finalclass_recall
     * @return NetworkMonitoring
     */
    public function setFunctionalciIdFinalclassRecall(?string $functionalci_id_finalclass_recall): static
    {
        $this->functionalci_id_finalclass_recall = $functionalci_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalciIdObsolescenceFlag(): ?string
    {
        return $this->functionalci_id_obsolescence_flag;
    }
    /**
     * @param string|null $functionalci_id_obsolescence_flag
     * @return NetworkMonitoring
     */
    public function setFunctionalciIdObsolescenceFlag(?string $functionalci_id_obsolescence_flag): static
    {
        $this->functionalci_id_obsolescence_flag = $functionalci_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleIdFriendlyname(): ?string
    {
        return $this->modele_id_friendlyname;
    }
    /**
     * @param string|null $modele_id_friendlyname
     * @return NetworkMonitoring
     */
    public function setModeleIdFriendlyname(?string $modele_id_friendlyname): static
    {
        $this->modele_id_friendlyname = $modele_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringBoxIdFriendlyname(): ?string
    {
        return $this->monitoring_box_id_friendlyname;
    }
    /**
     * @param string|null $monitoring_box_id_friendlyname
     * @return NetworkMonitoring
     */
    public function setMonitoringBoxIdFriendlyname(?string $monitoring_box_id_friendlyname): static
    {
        $this->monitoring_box_id_friendlyname = $monitoring_box_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringBoxIdFinalclassRecall(): ?string
    {
        return $this->monitoring_box_id_finalclass_recall;
    }
    /**
     * @param string|null $monitoring_box_id_finalclass_recall
     * @return NetworkMonitoring
     */
    public function setMonitoringBoxIdFinalclassRecall(?string $monitoring_box_id_finalclass_recall): static
    {
        $this->monitoring_box_id_finalclass_recall = $monitoring_box_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringBoxIdObsolescenceFlag(): ?string
    {
        return $this->monitoring_box_id_obsolescence_flag;
    }
    /**
     * @param string|null $monitoring_box_id_obsolescence_flag
     * @return NetworkMonitoring
     */
    public function setMonitoringBoxIdObsolescenceFlag(?string $monitoring_box_id_obsolescence_flag): static
    {
        $this->monitoring_box_id_obsolescence_flag = $monitoring_box_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getControleIdFriendlyname(): ?string
    {
        return $this->controle_id_friendlyname;
    }
    /**
     * @param string|null $controle_id_friendlyname
     * @return NetworkMonitoring
     */
    public function setControleIdFriendlyname(?string $controle_id_friendlyname): static
    {
        $this->controle_id_friendlyname = $controle_id_friendlyname;
        return $this;
    }
}