<?php

namespace Zorille\salesforce\data_models;

use Exception;
use Zorille\salesforce\data_model;

/**
 * @method Opportunity getOpportunity()
 * @method Asset getAsset()
 */
class OpportunityProduct extends data_model
{
    const ENTITY_NAME = 'OpportunityProduct';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ],
        'Opportunity' => [
            'class' => Opportunity::class,
            'value' => null
        ],
        'Asset' => [
            'class' => Asset::class,
            'value' => null
        ]
    ];

    protected ?string $id = null;
    protected ?string $opportunityId = null;
    protected ?string $sortOrder = null;
    protected ?string $pricebookEntryId = null;
    protected ?string $product2Id = null;
    protected ?string $productCode = null;
    protected ?string $name = null;
    protected ?string $currencyIsoCode = null;
    protected ?float $quantity = null;
    protected ?float $subtotal = null;
    protected ?float $totalPrice = null;
    protected ?float $unitPrice = null;
    protected ?float $listPrice = null;
    protected ?string $serviceDate = null;
    protected ?bool $hasRevenueSchedule = false;
    protected ?bool $hasQuantitySchedule = false;
    protected ?string $description = null;
    protected bool $hasSchedule = false;
    protected bool $canUseRevenueSchedule = false;
    protected ?string $createdDate = null;
    protected ?string $createdById = null;
    protected ?string $lastModifiedDate = null;
    protected ?string $lastModifiedById = null;
    protected ?string $systemModstamp = null;
    protected bool $isDeleted = false;
    protected ?string $lastViewedDate = null;
    protected ?string $lastReferencedDate = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOpportunityId(): ?string
    {
        return $this->opportunityId;
    }

    public function setOpportunityId(?string $opportunityId): self
    {
        $this->opportunityId = $opportunityId;

        return $this;
    }

    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getPricebookEntryId(): ?string
    {
        return $this->pricebookEntryId;
    }

    public function setPricebookEntryId(?string $pricebookEntryId): self
    {
        $this->pricebookEntryId = $pricebookEntryId;

        return $this;
    }

    public function getProduct2Id(): ?string
    {
        return $this->product2Id;
    }

    public function setProduct2Id(?string $product2Id): self
    {
        $this->product2Id = $product2Id;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(?float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getListPrice(): ?float
    {
        return $this->listPrice;
    }

    public function setListPrice(?float $listPrice): self
    {
        $this->listPrice = $listPrice;

        return $this;
    }

    public function getServiceDate(): ?string
    {
        return $this->serviceDate;
    }

    public function setServiceDate(?string $serviceDate): self
    {
        $this->serviceDate = $serviceDate;

        return $this;
    }

    public function isHasRevenueSchedule(): ?bool
    {
        return $this->hasRevenueSchedule;
    }

    public function setHasRevenueSchedule(?bool $hasRevenueSchedule): self
    {
        $this->hasRevenueSchedule = $hasRevenueSchedule;

        return $this;
    }

    public function isHasQuantitySchedule(): ?bool
    {
        return $this->hasQuantitySchedule;
    }

    public function setHasQuantitySchedule(?bool $hasQuantitySchedule): self
    {
        $this->hasQuantitySchedule = $hasQuantitySchedule;

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

    public function isHasSchedule(): ?bool
    {
        return $this->hasSchedule;
    }

    public function setHasSchedule(?bool $hasSchedule): self
    {
        $this->hasSchedule = $hasSchedule;

        return $this;
    }

    public function isCanUseRevenueSchedule(): ?bool
    {
        return $this->canUseRevenueSchedule;
    }

    public function setCanUseRevenueSchedule(?bool $canUseRevenueSchedule): self
    {
        $this->canUseRevenueSchedule = $canUseRevenueSchedule;

        return $this;
    }

    public function getCreatedDate(): ?string
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?string $createdDate): self
    {
        $this->createdDate = $createdDate;

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

    public function getLastModifiedDate(): ?string
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(?string $lastModifiedDate): self
    {
        $this->lastModifiedDate = $lastModifiedDate;

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

    public function getSystemModstamp(): ?string
    {
        return $this->systemModstamp;
    }

    public function setSystemModstamp(?string $systemModstamp): self
    {
        $this->systemModstamp = $systemModstamp;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getLastViewedDate(): ?string
    {
        return $this->lastViewedDate;
    }

    public function setLastViewedDate(?string $lastViewedDate): self
    {
        $this->lastViewedDate = $lastViewedDate;

        return $this;
    }

    public function getLastReferencedDate(): ?string
    {
        return $this->lastReferencedDate;
    }

    public function setLastReferencedDate(?string $lastReferencedDate): self
    {
        $this->lastReferencedDate = $lastReferencedDate;

        return $this;
    }

    public function setOpportunity(?array $opportunity): self
    {
        $class = $this->virtualProperties['Opportunity']['class'];
        $model = !is_null($opportunity) && !empty($class) ? $class::convert($opportunity) : $opportunity;

        $this->virtualProperties['Opportunity']['value'] = $model;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setAsset(array|Asset|null $asset): self
    {
        $class = $this->virtualProperties['Asset']['class'];

        $model = !is_null($asset) && !is_object($asset) && !empty($class) ? $class::convert($asset) : $asset;

        if (is_object($asset) && !($model instanceof $class)) {
            throw new Exception("object must be of type {$class}");
        }

        $this->virtualProperties['Asset']['value'] = $model;

        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $modelClass = $this->virtualProperties['Attributes']['class'];
        $this->virtualProperties['Attributes']['value'] = $modelClass::convert($attributes);

        return $this;
    }
}