<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class OSMonitoring extends data_model
{
    const ENTITY_NAME = 'OSMonitoring';

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $org_id = null;
    protected ?string $organization_name = null;
    protected ?string $organization_euclyde_id = null;
    protected ?string $business_criticity = null;
    protected ?string $status = null;
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
    protected ?string $monitoring_host_wmi = null;
    protected ?string $monitoring_host_ssh = null;
    protected ?string $monitoring_host_api = null;
    protected ?string $appsmonitoring_list = null;
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
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return OSMonitoring
     */
    public function setId(?string $id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setName(?string $name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setOrgId(?string $org_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setOrganizationName(?string $organization_name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setOrganizationEuclydeId(?string $organization_euclyde_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setBusinessCriticity(?string $business_criticity): OSMonitoring
    {
        $this->business_criticity = $business_criticity;
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
     * @return OSMonitoring
     */
    public function setStatus(?string $status): OSMonitoring
    {
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
     * @return OSMonitoring
     */
    public function setFunctionalciId(?string $functionalci_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setFunctionalciName(?string $functionalci_name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setModeleId(?string $modele_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setModeleName(?string $modele_name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringId(?string $monitoring_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringUrl(?string $monitoring_url): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setEtiquettes(?string $etiquettes): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringBoxId(?string $monitoring_box_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringBoxName(?string $monitoring_box_name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setControleId(?string $controle_id): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setControleName(?string $controle_name): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setSeuilControlAlerte(?string $seuil_control_alerte): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setSeuilControlCritique(?string $seuil_control_critique): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setCategory(?string $category): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringHostStandardAccount(?string $monitoring_host_standard_account): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringHostSnmp(?string $monitoring_host_snmp): OSMonitoring
    {
        $this->monitoring_host_snmp = $monitoring_host_snmp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostWmi(): ?string
    {
        return $this->monitoring_host_wmi;
    }

    /**
     * @param string|null $monitoring_host_wmi
     * @return OSMonitoring
     */
    public function setMonitoringHostWmi(?string $monitoring_host_wmi): OSMonitoring
    {
        $this->monitoring_host_wmi = $monitoring_host_wmi;
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
     * @return OSMonitoring
     */
    public function setMonitoringHostSsh(?string $monitoring_host_ssh): OSMonitoring
    {
        $this->monitoring_host_ssh = $monitoring_host_ssh;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostApi(): ?string
    {
        return $this->monitoring_host_api;
    }

    /**
     * @param string|null $monitoring_host_api
     * @return OSMonitoring
     */
    public function setMonitoringHostApi(?string $monitoring_host_api): OSMonitoring
    {
        $this->monitoring_host_api = $monitoring_host_api;
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
     * @return OSMonitoring
     */
    public function setAppsmonitoringList(?string $appsmonitoring_list): OSMonitoring
    {
        $this->appsmonitoring_list = $appsmonitoring_list;
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
     * @return OSMonitoring
     */
    public function setFinalclass(?string $finalclass): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setFriendlyname(?string $friendlyname): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setObsolescenceDate(?string $obsolescence_date): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setFunctionalciIdFriendlyname(?string $functionalci_id_friendlyname): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setFunctionalciIdFinalclassRecall(?string $functionalci_id_finalclass_recall): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setFunctionalciIdObsolescenceFlag(?string $functionalci_id_obsolescence_flag): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setModeleIdFriendlyname(?string $modele_id_friendlyname): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringBoxIdFriendlyname(?string $monitoring_box_id_friendlyname): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringBoxIdFinalclassRecall(?string $monitoring_box_id_finalclass_recall): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setMonitoringBoxIdObsolescenceFlag(?string $monitoring_box_id_obsolescence_flag): OSMonitoring
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
     * @return OSMonitoring
     */
    public function setControleIdFriendlyname(?string $controle_id_friendlyname): OSMonitoring
    {
        $this->controle_id_friendlyname = $controle_id_friendlyname;
        return $this;
    }
}