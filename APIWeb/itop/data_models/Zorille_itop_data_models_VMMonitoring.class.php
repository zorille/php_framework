<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class VMMonitoring extends data_model
{
    const ENTITY_NAME = 'VMMonitoring';

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
    protected ?string $category = null;
    protected ?string $modele_vmwaretools_id = null;
    protected ?string $modele_vmwaretools_name = null;
    protected ?string $monitoring_virtu_standard_account = null;
    protected ?string $appsmonitoring_list = null;
    protected string $finalclass = 'VMMonitoring';
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
    protected ?string $modele_vmwaretools_id_friendlyname = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    /**
     * @param string|null $id
     * @return AppsMonitoring
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
     * @return VMMonitoring
     */
    public function setName(?string $name): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setOrgId(?string $org_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setOrganizationName(?string $organization_name): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setOrganizationEuclydeId(?string $organization_euclyde_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setBusinessCriticity(?string $business_criticity): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setStatus(?string $status): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFunctionalciId(?string $functionalci_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFunctionalciName(?string $functionalci_name): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setModeleId(?string $modele_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setModeleName(?string $modele_name): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringId(?string $monitoring_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringUrl(?string $monitoring_url): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setEtiquettes(?string $etiquettes): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringBoxId(?string $monitoring_box_id): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringBoxName(?string $monitoring_box_name): VMMonitoring
    {
        $this->monitoring_box_name = $monitoring_box_name;
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
     * @return VMMonitoring
     */
    public function setCategory(?string $category): VMMonitoring
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleVmwaretoolsId(): ?string
    {
        return $this->modele_vmwaretools_id;
    }
    /**
     * @param string|null $modele_vmwaretools_id
     * @return VMMonitoring
     */
    public function setModeleVmwaretoolsId(?string $modele_vmwaretools_id): VMMonitoring
    {
        $this->modele_vmwaretools_id = $modele_vmwaretools_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleVmwaretoolsName(): ?string
    {
        return $this->modele_vmwaretools_name;
    }
    /**
     * @param string|null $modele_vmwaretools_name
     * @return VMMonitoring
     */
    public function setModeleVmwaretoolsName(?string $modele_vmwaretools_name): VMMonitoring
    {
        $this->modele_vmwaretools_name = $modele_vmwaretools_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringVirtuStandardAccount(): ?string
    {
        return $this->monitoring_virtu_standard_account;
    }
    /**
     * @param string|null $monitoring_virtu_standard_account
     * @return VMMonitoring
     */
    public function setMonitoringVirtuStandardAccount(?string $monitoring_virtu_standard_account): VMMonitoring
    {
        $this->monitoring_virtu_standard_account = $monitoring_virtu_standard_account;
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
     * @return VMMonitoring
     */
    public function setAppsmonitoringList(?string $appsmonitoring_list): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFinalclass(?string $finalclass): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFriendlyname(?string $friendlyname): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setObsolescenceDate(?string $obsolescence_date): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFunctionalciIdFriendlyname(?string $functionalci_id_friendlyname): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFunctionalciIdFinalclassRecall(?string $functionalci_id_finalclass_recall): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setFunctionalciIdObsolescenceFlag(?string $functionalci_id_obsolescence_flag): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setModeleIdFriendlyname(?string $modele_id_friendlyname): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringBoxIdFriendlyname(?string $monitoring_box_id_friendlyname): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringBoxIdFinalclassRecall(?string $monitoring_box_id_finalclass_recall): VMMonitoring
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
     * @return VMMonitoring
     */
    public function setMonitoringBoxIdObsolescenceFlag(?string $monitoring_box_id_obsolescence_flag): VMMonitoring
    {
        $this->monitoring_box_id_obsolescence_flag = $monitoring_box_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModeleVmwaretoolsIdFriendlyname(): ?string
    {
        return $this->modele_vmwaretools_id_friendlyname;
    }
    /**
     * @param string|null $modele_vmwaretools_id_friendlyname
     * @return VMMonitoring
     */
    public function setModeleVmwaretoolsIdFriendlyname(?string $modele_vmwaretools_id_friendlyname): VMMonitoring
    {
        $this->modele_vmwaretools_id_friendlyname = $modele_vmwaretools_id_friendlyname;
        return $this;
    }
}