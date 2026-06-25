<?php

namespace Zorille\itop\data_models;

/**
 * @method static self create()
 * @method void setOrganization(Organization|array $organization)
 * @method Organization getOrganization()
 */
class CrmAsset extends FunctionalCI
{
    const ENTITY_NAME = 'CrmAssets';

    const STATUS_IMPLEMENTATION = 'implementation';
    const STATUS_OBSOLETE = 'obsolete';
    const STATUS_DECOMMISSIONING = 'decommissionning';
    const STATUS_PRODUCTION = 'production';

    protected array $virtualProperties = [
        'Organization' => [
            'class' => Organization::class,
            'value' => null
        ]
    ];

    protected ?string $billing_code = null;
    protected string $status = self::STATUS_IMPLEMENTATION;
    protected ?string $crm_reference = null;
    /**
     * @var array<array{
     *     functionalci_id: string,
     *     friendlyname: string,
     *     functionalci_id_friendlyname: string,
     *     functionalci_id_finalclass_recall: string,
     *     functionalci_id_obsolescence_flag: string,
     * }> $functionalcis_list
     */
    protected array $functionalcis_list = [];
    protected ?int $location_id = null;
    protected string $finalclass = 'CrmAssets';
    protected ?string $obsolescence_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $crm_sof_number = null;

    public function getBillingCode(): ?string
    {
        return $this->billing_code;
    }
    public function setBillingCode(?string $billing_code): self
    {
        $this->billing_code = $billing_code;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCrmReference(): ?string
    {
        return $this->crm_reference;
    }
    public function setCrmReference(?string $crm_reference): self
    {
        $this->crm_reference = $crm_reference;
        return $this;
    }

    /**
     * @return array<array{
     *     functionalci_id: string,
     *     friendlyname: string,
     *     functionalci_id_friendlyname: string,
     *     functionalci_id_finalclass_recall: string,
     *     functionalci_id_obsolescence_flag: string,
     * }>
     */
    public function getFunctionalcisList(): array
    {
        return $this->functionalcis_list;
    }
    public function setFunctionalcisList(array $functionalcis_list): self
    {
        $this->functionalcis_list = $functionalcis_list;
        return $this;
    }

    public function getLocationId(): ?int
    {
        return $this->location_id;
    }
    public function setLocationId(?int $location_id): self
    {
        $this->location_id = $location_id;
        return $this;
    }

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }
    public function setObsolescenceFlag(?string $obsolescence_flag): self
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getLocationIdFriendlyname(): ?string
    {
        return $this->location_id_friendlyname;
    }
    public function setLocationIdFriendlyname(?string $location_id_friendlyname): self
    {
        $this->location_id_friendlyname = $location_id_friendlyname;
        return $this;
    }

    public function getLocationIdObsolescenceFlag(): ?string
    {
        return $this->location_id_obsolescence_flag;
    }
    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): self
    {
        $this->location_id_obsolescence_flag = $location_id_obsolescence_flag;
        return $this;
    }

    public function getCrmSofNumber(): ?string
    {
        return $this->crm_sof_number;
    }
    public function setCrmSofNumber(?string $crm_sof_number): CrmAsset
    {
        $this->crm_sof_number = $crm_sof_number;
        return $this;
    }
}