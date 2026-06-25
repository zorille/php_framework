<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

/**
 * @method null|Order getOrder()
 * @method self setOrder(Order|array $order)
 */
class Asset extends data_model
{
    const ENTITY_NAME = 'Asset';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ],
        'Order' => [
            'class' => Order::class,
            'value' => null
        ]
    ];

    protected ?string $id = null;
    protected ?string $order__c = null;
    protected ?string $contactId = null;
    protected ?string $accountId = null;
    protected ?string $parentId = null;
    protected ?string $rootAssetId = null;
    protected ?string $product2Id = null;
    protected ?string $productCode = null;
    protected ?string $productFamily = null;
    protected ?string $productDescription = null;
    protected ?bool $isCompetitorProduct = false;
    protected ?string $createdDate = null;
    protected ?string $createdById = null;
    protected ?string $lastModifiedDate = null;
	protected ?string $lastModifiedById = null;
	protected ?string $systemModstamp = null;
	protected ?bool $isDeleted = false;
	protected ?string $currencyIsoCode = null;
	protected ?string $name = null;
	protected ?string $serialNumber = null;
	protected ?string $installDate = null;
	protected ?string $manufactureDate = null;
	protected ?string $statusReason = null;
	protected ?string $uuid = null;
	protected ?string $externalIdentifier = null;
	protected ?string $purchaseDate = null;
	protected ?string $usageEndDate = null;
	protected ?string $status = null;
	protected ?string $digitalAssetStatus = null;
	protected ?float $price = null;
	protected ?float $quantity = 1.0;
	protected ?string $description = null;
	protected ?string $ownerId = null;
	protected ?string $assetProvidedById = null;
	protected ?string $assetServicedById = null;
	protected ?bool $isInternal = false;
	protected ?int $assetLevel = 1;
	protected ?string $stockKeepingUnit = null;
	protected ?string $consequenceOfFailure = null;
	protected ?string $street = null;
	protected ?string $city = null;
	protected ?string $state = null;
	protected ?string $postalCode = null;
	protected ?string $country = null;
	protected ?float $latitude = null;
	protected ?float $longitude = null;
	protected ?string $geocodeAccuracy = null;
	protected ?string $address = null;
	protected ?string $lastViewedDate = null;
	protected ?string $lastReferencedDate = null;
	protected ?string $billing_Code__c = null;
	protected ?string $customer_Number__c = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderC(): ?string
    {
        return $this->order__c;
    }

    public function setOrderC(?string $order__c): self
    {
        $this->order__c = $order__c;

        return $this;
    }

    public function getContactId(): ?string
    {
        return $this->contactId;
    }

    public function setContactId(?string $contactId): self
    {
        $this->contactId = $contactId;

        return $this;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getRootAssetId(): ?string
    {
        return $this->rootAssetId;
    }

    public function setRootAssetId(?string $rootAssetId): self
    {
        $this->rootAssetId = $rootAssetId;

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

    public function getProductFamily(): ?string
    {
        return $this->productFamily;
    }

    public function setProductFamily(?string $productFamily): self
    {
        $this->productFamily = $productFamily;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(?string $productDescription): self
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function isCompetitorProduct(): ?bool
    {
        return $this->isCompetitorProduct;
    }

    public function setIsCompetitorProduct(?bool $isCompetitorProduct): self
    {
        $this->isCompetitorProduct = $isCompetitorProduct;

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

    public function getCurrencyIsoCode(): ?string
    {
        return $this->currencyIsoCode;
    }

    public function setCurrencyIsoCode(?string $currencyIsoCode): self
    {
        $this->currencyIsoCode = $currencyIsoCode;

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

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getInstallDate(): ?string
    {
        return $this->installDate;
    }

    public function setInstallDate(?string $installDate): self
    {
        $this->installDate = $installDate;

        return $this;
    }

    public function getManufactureDate(): ?string
    {
        return $this->manufactureDate;
    }

    public function setManufactureDate(?string $manufactureDate): self
    {
        $this->manufactureDate = $manufactureDate;

        return $this;
    }

    public function getStatusReason(): ?string
    {
        return $this->statusReason;
    }

    public function setStatusReason(?string $statusReason): self
    {
        $this->statusReason = $statusReason;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getExternalIdentifier(): ?string
    {
        return $this->externalIdentifier;
    }

    public function setExternalIdentifier(?string $externalIdentifier): self
    {
        $this->externalIdentifier = $externalIdentifier;

        return $this;
    }

    public function getPurchaseDate(): ?string
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?string $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getUsageEndDate(): ?string
    {
        return $this->usageEndDate;
    }

    public function setUsageEndDate(?string $usageEndDate): self
    {
        $this->usageEndDate = $usageEndDate;

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

    public function getDigitalAssetStatus(): ?string
    {
        return $this->digitalAssetStatus;
    }

    public function setDigitalAssetStatus(?string $digitalAssetStatus): self
    {
        $this->digitalAssetStatus = $digitalAssetStatus;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    public function setOwnerId(?string $ownerId): self
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getAssetProvidedById(): ?string
    {
        return $this->assetProvidedById;
    }

    public function setAssetProvidedById(?string $assetProvidedById): self
    {
        $this->assetProvidedById = $assetProvidedById;

        return $this;
    }

    public function getAssetServicedById(): ?string
    {
        return $this->assetServicedById;
    }

    public function setAssetServicedById(?string $assetServicedById): self
    {
        $this->assetServicedById = $assetServicedById;

        return $this;
    }

    public function isInternal(): ?bool
    {
        return $this->isInternal;
    }

    public function setIsInternal(?bool $isInternal): self
    {
        $this->isInternal = $isInternal;

        return $this;
    }

    public function getAssetLevel(): ?int
    {
        return $this->assetLevel;
    }

    public function setAssetLevel(?int $assetLevel): self
    {
        $this->assetLevel = $assetLevel;

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

    public function getConsequenceOfFailure(): ?string
    {
        return $this->consequenceOfFailure;
    }

    public function setConsequenceOfFailure(?string $consequenceOfFailure): self
    {
        $this->consequenceOfFailure = $consequenceOfFailure;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getGeocodeAccuracy(): ?string
    {
        return $this->geocodeAccuracy;
    }

    public function setGeocodeAccuracy(?string $geocodeAccuracy): self
    {
        $this->geocodeAccuracy = $geocodeAccuracy;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getBillingCodeC(): ?string
    {
        return $this->billing_Code__c;
    }

    public function setBillingCodeC(?string $billing_Code__c): self
    {
        $this->billing_Code__c = $billing_Code__c;

        return $this;
    }

    public function getCustomerNumberC(): ?string
    {
        return $this->customer_Number__c;
    }

    public function setCustomerNumberC(?string $customer_Number__c): self
    {
        $this->customer_Number__c = $customer_Number__c;

        return $this;
    }
}