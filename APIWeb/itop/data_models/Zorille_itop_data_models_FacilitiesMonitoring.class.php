<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class FacilitiesMonitoring extends data_model
{
    const ENTITY_NAME = 'FacilitiesMonitoring';

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $org_id = null;
    protected ?string $organization_name = null;
    protected ?string $organization_euclyde_id = null;
    protected ?CriticityEnum $business_criticity = CriticityEnum::LOW;
    protected ?StatusEnum $status = null;
    protected ?string $functional_id = null;
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
    protected ?string $monitoring_collecte_elect = null;
    protected ?string $appsmonitoring_list = null;
    protected string $finalclass = 'FacilitiesMonitoring';
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $obsolescence_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $functionalci_id = null;
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
     * @return static
     */
    public function setId(?string $id): static

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
     * @return static
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
     * @return static
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
     * @return static

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
     * @return static
     */
    public function setOrganizationEuclydeId(?string $organization_euclyde_id): static

    {
        $this->organization_euclyde_id = $organization_euclyde_id;
        return $this;
    }

    /**
     * @return CriticityEnum|null
     */
    public function getBusinessCriticity(): ?CriticityEnum
    {
        return $this->business_criticity;
    }
    /**
     * @param CriticityEnum|null $business_criticity
     * @return static
     */
    public function setBusinessCriticity(?CriticityEnum $business_criticity): static

    {
        $this->business_criticity = $business_criticity;
        return $this;
    }

    /**
     * @return StatusEnum|null
     */
    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }
    /**
     * @param StatusEnum|null $status
     * @return static
     */
    public function setStatus(?StatusEnum $status): static

    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunctionalId(): ?string
    {
        return $this->functional_id;
    }
    /**
     * @param string|null $functional_id
     * @return static
     */
    public function setFunctionalId(?string $functional_id): static

    {
        $this->functional_id = $functional_id;
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setMonitoringHostSnmp(?string $monitoring_host_snmp): static

    {
        $this->monitoring_host_snmp = $monitoring_host_snmp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringCollecteElect(): ?string
    {
        return $this->monitoring_collecte_elect;
    }
    /**
     * @param string|null $monitoring_collecte_elect
     * @return static
     */
    public function setMonitoringCollecteElect(?string $monitoring_collecte_elect): static

    {
        $this->monitoring_collecte_elect = $monitoring_collecte_elect;
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
     * @return static
     */
    public function setAppsmonitoringList(?string $appsmonitoring_list): static

    {
        $this->appsmonitoring_list = $appsmonitoring_list;
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
     * @return static
     */
    public function setFinalclass(string $finalclass): static

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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setFunctionalciIdFriendlyname(?string $functionalci_id_friendlyname): static

    {
        $this->functionalci_id_friendlyname = $functionalci_id_friendlyname;
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
     * @return static
     */
    public function setFunctionalciId(?string $functionalci_id): static

    {
        $this->functionalci_id = $functionalci_id;
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setControleIdFriendlyname(?string $controle_id_friendlyname): static

    {
        $this->controle_id_friendlyname = $controle_id_friendlyname;
        return $this;
    }
}