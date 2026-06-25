<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class Service extends data_model
{
    const ENTITY_NAME = 'Service';

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $org_id = null;
    protected ?string $organisation_name = null;
    protected ?string $servicefamily_id = null;
    protected ?string $servicefamily_name = null;
    protected ?string $description = null;
    protected array $documents_list = [];
    protected ?string $icon = null;
    protected array $customercontracts_list = [];
    protected array $servicesubcategories_list = [];
    protected ?string $productcode = null;
    protected ?string $friendlyname = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $servicefamily_id_friendlyname = null;
    protected ?string $billingcode = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Service
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Service
    {
        $this->name = $name;
        return $this;
    }

    public function getOrgId(): ?string
    {
        return $this->org_id;
    }

    public function setOrgId(?string $org_id): Service
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrganisationName(): ?string
    {
        return $this->organisation_name;
    }

    public function setOrganisationName(?string $organisation_name): Service
    {
        $this->organisation_name = $organisation_name;
        return $this;
    }

    public function getServicefamilyId(): ?string
    {
        return $this->servicefamily_id;
    }

    public function setServicefamilyId(?string $servicefamily_id): Service
    {
        $this->servicefamily_id = $servicefamily_id;
        return $this;
    }

    public function getServicefamilyName(): ?string
    {
        return $this->servicefamily_name;
    }

    public function setServicefamilyName(?string $servicefamily_name): Service
    {
        $this->servicefamily_name = $servicefamily_name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Service
    {
        $this->description = $description;
        return $this;
    }

    public function getDocumentsList(): array
    {
        return $this->documents_list;
    }

    public function setDocumentsList(array $documents_list): Service
    {
        $this->documents_list = $documents_list;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): Service
    {
        $this->icon = $icon;
        return $this;
    }

    public function getCustomercontractsList(): array
    {
        return $this->customercontracts_list;
    }

    public function setCustomercontractsList(array $customercontracts_list): Service
    {
        $this->customercontracts_list = $customercontracts_list;
        return $this;
    }

    public function getServicesubcategoriesList(): array
    {
        return $this->servicesubcategories_list;
    }

    public function setServicesubcategoriesList(array $servicesubcategories_list): Service
    {
        $this->servicesubcategories_list = $servicesubcategories_list;
        return $this;
    }

    public function getProductcode(): ?string
    {
        return $this->productcode;
    }

    public function setProductcode(?string $productcode): Service
    {
        $this->productcode = $productcode;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    public function setFriendlyname(?string $friendlyname): Service
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }

    public function setOrgIdFriendlyname(?string $org_id_friendlyname): Service
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }

    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): Service
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }

    public function getServicefamilyIdFriendlyname(): ?string
    {
        return $this->servicefamily_id_friendlyname;
    }

    public function setServicefamilyIdFriendlyname(?string $servicefamily_id_friendlyname): Service
    {
        $this->servicefamily_id_friendlyname = $servicefamily_id_friendlyname;
        return $this;
    }

    public function getBillingcode(): ?string
    {
        return $this->billingcode;
    }

    public function setBillingcode(?string $billingcode): Service
    {
        $this->billingcode = $billingcode;
        return $this;
    }
}