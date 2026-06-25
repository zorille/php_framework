<?php

namespace Zorille\salesforce\data_models;

use Exception;
use Zorille\salesforce\data_model;

/**
 * @method Opportunity getOpportunity()
 * @method Asset getAsset()
 */
class Product2 extends data_model
{
    const ENTITY_NAME = 'Product2';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ]
    ];

    protected ?string $id = null;
    protected bool $isActive = false;
    protected ?string $createdById = null;
    protected ?string $displayUrl = null;
    protected ?string $externalDataSourceId = null;
    protected ?string $externalId = null;
    protected ?string $lastModifiedById = null;
    protected ?int $numberOfRevenueInstallments = null;
    protected ?string $productCode = null;
    protected ?string $currencyIsoCode = null;
    protected ?string $description = null;
    protected ?string $family = null;
    protected ?string $name = null;
    protected ?string $stockKeepingUnit = null;
    protected ?string $quantityUnitOfMeasure = null;
    protected ?string $revenueInstallmentPeriod = null;
    protected ?string $revenueScheduleType = null;
    protected bool $canUseRevenueSchedule = false;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $cabinet_Type__c): self
    {
        $this->id = $cabinet_Type__c;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedById(): ?string
    {
        return $this->createdById;
    }
    public function setCreatedById(?string $createdById): self
    {
        $this->createdById = $createdById;
        return $this;
    }

    public function getDisplayUrl(): ?string
    {
        return $this->displayUrl;
    }
    public function setDisplayUrl(?string $displayUrl): self
    {
        $this->displayUrl = $displayUrl;
        return $this;
    }

    public function getExternalDataSourceId(): ?string
    {
        return $this->externalDataSourceId;
    }
    public function setExternalDataSourceId(?string $externalDataSourceId): self
    {
        $this->externalDataSourceId = $externalDataSourceId;
        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function getLastModifiedById(): ?string
    {
        return $this->lastModifiedById;
    }
    public function setLastModifiedById(?string $lastModifiedById): self
    {
        $this->lastModifiedById = $lastModifiedById;
        return $this;
    }

    public function getNumberOfRevenueInstallments(): ?int
    {
        return $this->numberOfRevenueInstallments;
    }
    public function setNumberOfRevenueInstallments(?int $numberOfRevenueInstallments): self
    {
        $this->numberOfRevenueInstallments = $numberOfRevenueInstallments;
        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }
    public function setProductCode(?string $productCode): self
    {
        $this->productCode = $productCode;
        return $this;
    }

    public function getCurrencyIsoCode(): ?string
    {
        return $this->currencyIsoCode;
    }
    public function setCurrencyIsoCode(?string $currencyIsoCode): self
    {
        $this->currencyIsoCode = $currencyIsoCode;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }
    public function setFamily(?string $family): self
    {
        $this->family = $family;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStockKeepingUnit(): ?string
    {
        return $this->stockKeepingUnit;
    }
    public function setStockKeepingUnit(?string $stockKeepingUnit): self
    {
        $this->stockKeepingUnit = $stockKeepingUnit;
        return $this;
    }

    public function getQuantityUnitOfMeasure(): ?string
    {
        return $this->quantityUnitOfMeasure;
    }
    public function setQuantityUnitOfMeasure(?string $quantityUnitOfMeasure): self
    {
        $this->quantityUnitOfMeasure = $quantityUnitOfMeasure;
        return $this;
    }

    public function getRevenueInstallmentPeriod(): ?string
    {
        return $this->revenueInstallmentPeriod;
    }
    public function setRevenueInstallmentPeriod(?string $revenueInstallmentPeriod): self
    {
        $this->revenueInstallmentPeriod = $revenueInstallmentPeriod;
        return $this;
    }

    public function getRevenueScheduleType(): ?string
    {
        return $this->revenueScheduleType;
    }
    public function setRevenueScheduleType(?string $revenueScheduleType): self
    {
        $this->revenueScheduleType = $revenueScheduleType;
        return $this;
    }

    public function isCanUseRevenueSchedule(): bool
    {
        return $this->canUseRevenueSchedule;
    }
    public function setCanUseRevenueSchedule(bool $canUseRevenueSchedule): self
    {
        $this->canUseRevenueSchedule = $canUseRevenueSchedule;
        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $modelClass = $this->virtualProperties['Attributes']['class'];
        $this->virtualProperties['Attributes']['value'] = $modelClass::convert($attributes);

        return $this;
    }
}