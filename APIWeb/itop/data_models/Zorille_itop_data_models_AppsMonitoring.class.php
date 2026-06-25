<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class AppsMonitoring extends data_model
{
    const ENTITY_NAME = 'AppsMonitoring';

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
    protected ?string $monitoring_host_id = null;
    protected ?string $monitoring_host_name = null;
    protected ?BinaryEnum $monitoring_apps_standard_account = BinaryEnum::YES;
    protected ?string $service_params = null;
    protected string $finalclass = 'AppsMonitoring';
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $modele_id_friendlyname = null;
    protected ?string $monitoring_box_id_friendlyname = null;
    protected ?string $monitoring_box_id_finalclass_recall = null;
    protected ?string $monitoring_box_id_obsolescence_flag = null;
    protected ?string $monitoring_host_id_friendlyname = null;
    protected ?string $monitoring_host_id_finalclass_recall = null;
    protected ?string $monitoring_host_id_obsolescence_flag = null;
    protected ?string $functionalci_id_finalclass_recall = null;

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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
     */
    public function setOrganizationEuclydeId(?string $organization_euclyde_id): static
    {
        $this->organization_euclyde_id = $organization_euclyde_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBusinessCriticity(): ?CriticityEnum
    {
        return $this->business_criticity;
    }
    /**
     * @param string|null $business_criticity
     * @return AppsMonitoring
     */
    public function setBusinessCriticity(null|string|CriticityEnum $business_criticity): static
    {
        if (is_string($business_criticity)) {
            $business_criticity = CriticityEnum::from($business_criticity);
        }
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
     * @param string|null $status
     * @return AppsMonitoring
     */
    public function setStatus(string|StatusEnum $status): static
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
    public function getFunctionalId(): ?string
    {
        return $this->functional_id;
    }
    /**
     * @param string|null $functional_id
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
     */
    public function setMonitoringBoxName(?string $monitoring_box_name): static
    {
        $this->monitoring_box_name = $monitoring_box_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostId(): ?string
    {
        return $this->monitoring_host_id;
    }
    /**
     * @param string|null $monitoring_host_id
     * @return AppsMonitoring
     */
    public function setMonitoringHostId(?string $monitoring_host_id): static
    {
        $this->monitoring_host_id = $monitoring_host_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostName(): ?string
    {
        return $this->monitoring_host_name;
    }
    /**
     * @param string|null $monitoring_host_name
     * @return AppsMonitoring
     */
    public function setMonitoringHostName(?string $monitoring_host_name): static
    {
        $this->monitoring_host_name = $monitoring_host_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringAppsStandardAccount(): ?BinaryEnum
    {
        return $this->monitoring_apps_standard_account;
    }
    /**
     * @param string|null $monitoring_apps_standard_account
     * @return AppsMonitoring
     */
    public function setMonitoringAppsStandardAccount(string|BinaryEnum $monitoring_apps_standard_account): static
    {
        if (is_string($monitoring_apps_standard_account)) {
            $monitoring_apps_standard_account = BinaryEnum::from($monitoring_apps_standard_account);
        }
        $this->monitoring_apps_standard_account = $monitoring_apps_standard_account;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getServiceParams(): ?string
    {
        return $this->service_params;
    }
    /**
     * @param string|null $service_params
     * @return AppsMonitoring
     */
    public function setServiceParams(?string $service_params): static
    {
        $this->service_params = $service_params;
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
     */
    public function setFriendlyname(?string $friendlyname): static
    {
        $this->friendlyname = $friendlyname;
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
     */
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): static
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
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
     * @return AppsMonitoring
     */
    public function setMonitoringBoxIdObsolescenceFlag(?string $monitoring_box_id_obsolescence_flag): static
    {
        $this->monitoring_box_id_obsolescence_flag = $monitoring_box_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostIdFriendlyname(): ?string
    {
        return $this->monitoring_host_id_friendlyname;
    }
    /**
     * @param string|null $monitoring_host_id_friendlyname
     * @return AppsMonitoring
     */
    public function setMonitoringHostIdFriendlyname(?string $monitoring_host_id_friendlyname): static
    {
        $this->monitoring_host_id_friendlyname = $monitoring_host_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostIdFinalclassRecall(): ?string
    {
        return $this->monitoring_host_id_finalclass_recall;
    }
    /**
     * @param string|null $monitoring_host_id_finalclass_recall
     * @return AppsMonitoring
     */
    public function setMonitoringHostIdFinalclassRecall(?string $monitoring_host_id_finalclass_recall): static
    {
        $this->monitoring_host_id_finalclass_recall = $monitoring_host_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMonitoringHostIdObsolescenceFlag(): ?string
    {
        return $this->monitoring_host_id_obsolescence_flag;
    }
    /**
     * @param string|null $monitoring_host_id_obsolescence_flag
     * @return AppsMonitoring
     */
    public function setMonitoringHostIdObsolescenceFlag(?string $monitoring_host_id_obsolescence_flag): static
    {
        $this->monitoring_host_id_obsolescence_flag = $monitoring_host_id_obsolescence_flag;
        return $this;
    }
}