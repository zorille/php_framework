<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

/**
 * @method Account getAccount()
 */
class Opportunity extends data_model
{
    const ENTITY_NAME = 'Opportunity';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ],
        'Account' => [
            'class' => Account::class,
            'value' => null
        ]
    ];

    protected ?string $id = "0066800000DvYQYAA3";
    protected ?bool $isDeleted = false;
    protected ?string $accountId = "0016800000VS1H2AAL";
    protected ?bool $isPrivate = false;
    protected ?string $name = "Exa_Inexio_New_12Cab_60kw_Stuttgart_Q1_2023";
    protected ?string $description = null;
    protected ?string $stageName = "Closed Won";
    protected ?float $amount = null;
    protected ?float $probability = 100.0;
    protected ?float $expectedRevenue = null;
    protected ?int $totalOpportunityQuantity = null;
    protected ?string $closeDate = "2023-04-01";
    protected ?string $type = "New Business";
    protected ?string $nextStep = null;
    protected ?string $leadSource = "Partner";
    protected ?bool $isClosed = true;
    protected ?bool $isWon = true;
    protected ?string $forecastCategory = "Closed";
    protected ?string $forecastCategoryName = "Closed";
    protected ?string $currencyIsoCode = "EUR";
    protected ?bool $hasOpportunityLineItem = false;
    protected ?string $pricebook2Id = null;
    protected ?string $ownerId = "00568000004Ag2lAAC";
    protected ?string $createdDate = "2023-05-11T15:35:25.000+0000";
    protected ?string $createdById = "00568000004Ag2lAAC";
    protected ?string $lastModifiedDate = "2024-01-31T11:58:04.000+0000";
    protected ?string $lastModifiedById = "00568000004AQLuAAO";
    protected ?string $systemModstamp = "2024-01-31T11:58:06.000+0000";
    protected ?string $lastActivityDate = null;
    protected ?int $pushCount = 0;
    protected ?string $lastStageChangeDate = null;
    protected ?int $fiscalQuarter = 2;
    protected ?int $fiscalYear = 2023;
    protected ?string $fiscal = "2023 2";
    protected ?string $contactId = null;
    protected ?string $lastViewedDate = null;
    protected?string  $lastReferencedDate = null;
    protected ?string $partnerAccountId = null;
    protected ?string $syncedQuoteId = null;
    protected ?bool $hasOpenActivity = false;
    protected ?bool $hasOverdueTask = false;
    protected ?string $lastAmountChangedHistoryId = null;
    protected ?string $lastCloseDateChangedHistoryId = "0086800000jtXPpAAM";

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        
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

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(?bool $isPrivate): self
    {
        $this->isPrivate = $isPrivate;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStageName(): ?string
    {
        return $this->stageName;
    }

    public function setStageName(?string $stageName): self
    {
        $this->stageName = $stageName;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getProbability(): ?float
    {
        return $this->probability;
    }

    public function setProbability(?float $probability): self
    {
        $this->probability = $probability;

        return $this;
    }

    public function getExpectedRevenue(): ?float
    {
        return $this->expectedRevenue;
    }

    public function setExpectedRevenue(?float $expectedRevenue): self
    {
        $this->expectedRevenue = $expectedRevenue;

        return $this;
    }

    public function getTotalOpportunityQuantity(): ?int
    {
        return $this->totalOpportunityQuantity;
    }

    public function setTotalOpportunityQuantity(?int $totalOpportunityQuantity): self
    {
        $this->totalOpportunityQuantity = $totalOpportunityQuantity;

        return $this;
    }

    public function getCloseDate(): ?string
    {
        return $this->closeDate;
    }

    public function setCloseDate(?string $closeDate): self
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNextStep(): ?string
    {
        return $this->nextStep;
    }

    public function setNextStep(?string $nextStep): self
    {
        $this->nextStep = $nextStep;

        return $this;
    }

    public function getLeadSource(): ?string
    {
        return $this->leadSource;
    }

    public function setLeadSource(?string $leadSource): self
    {
        $this->leadSource = $leadSource;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(?bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function isWon(): ?bool
    {
        return $this->isWon;
    }

    public function setIsWon(?bool $isWon): self
    {
        $this->isWon = $isWon;

        return $this;
    }

    public function getForecastCategory(): ?string
    {
        return $this->forecastCategory;
    }

    public function setForecastCategory(?string $forecastCategory): self
    {
        $this->forecastCategory = $forecastCategory;

        return $this;
    }

    public function getForecastCategoryName(): ?string
    {
        return $this->forecastCategoryName;
    }

    public function setForecastCategoryName(?string $forecastCategoryName): self
    {
        $this->forecastCategoryName = $forecastCategoryName;

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

    public function isHasOpportunityLineItem(): ?bool
    {
        return $this->hasOpportunityLineItem;
    }

    public function setHasOpportunityLineItem(?bool $hasOpportunityLineItem): self
    {
        $this->hasOpportunityLineItem = $hasOpportunityLineItem;

        return $this;
    }

    public function getPricebook2Id(): ?string
    {
        return $this->pricebook2Id;
    }

    public function setPricebook2Id(?string $pricebook2Id): self
    {
        $this->pricebook2Id = $pricebook2Id;

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

    public function getLastActivityDate(): ?string
    {
        return $this->lastActivityDate;
    }

    public function setLastActivityDate(?string $lastActivityDate): self
    {
        $this->lastActivityDate = $lastActivityDate;

        return $this;
    }

    public function getPushCount(): ?int
    {
        return $this->pushCount;
    }

    public function setPushCount(?int $pushCount): self
    {
        $this->pushCount = $pushCount;

        return $this;
    }

    public function getLastStageChangeDate(): ?string
    {
        return $this->lastStageChangeDate;
    }

    public function setLastStageChangeDate(?string $lastStageChangeDate): self
    {
        $this->lastStageChangeDate = $lastStageChangeDate;

        return $this;
    }

    public function getFiscalQuarter(): ?int
    {
        return $this->fiscalQuarter;
    }

    public function setFiscalQuarter(?int $fiscalQuarter): self
    {
        $this->fiscalQuarter = $fiscalQuarter;

        return $this;
    }

    public function getFiscalYear(): ?int
    {
        return $this->fiscalYear;
    }

    public function setFiscalYear(?int $fiscalYear): self
    {
        $this->fiscalYear = $fiscalYear;

        return $this;
    }

    public function getFiscal(): ?string
    {
        return $this->fiscal;
    }

    public function setFiscal(?string $fiscal): self
    {
        $this->fiscal = $fiscal;

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

    public function getPartnerAccountId(): ?string
    {
        return $this->partnerAccountId;
    }

    public function setPartnerAccountId(?string $partnerAccountId): self
    {
        $this->partnerAccountId = $partnerAccountId;

        return $this;
    }

    public function getSyncedQuoteId(): ?string
    {
        return $this->syncedQuoteId;
    }

    public function setSyncedQuoteId(?string $syncedQuoteId): self
    {
        $this->syncedQuoteId = $syncedQuoteId;

        return $this;
    }

    public function isHasOpenActivity(): ?bool
    {
        return $this->hasOpenActivity;
    }

    public function setHasOpenActivity(?bool $hasOpenActivity): self
    {
        $this->hasOpenActivity = $hasOpenActivity;

        return $this;
    }

    public function isHasOverdueTask(): ?bool
    {
        return $this->hasOverdueTask;
    }

    public function setHasOverdueTask(?bool $hasOverdueTask): self
    {
        $this->hasOverdueTask = $hasOverdueTask;

        return $this;
    }

    public function getLastAmountChangedHistoryId(): ?string
    {
        return $this->lastAmountChangedHistoryId;
    }

    public function setLastAmountChangedHistoryId(?string $lastAmountChangedHistoryId): self
    {
        $this->lastAmountChangedHistoryId = $lastAmountChangedHistoryId;

        return $this;
    }

    public function getLastCloseDateChangedHistoryId(): ?string
    {
        return $this->lastCloseDateChangedHistoryId;
    }

    public function setLastCloseDateChangedHistoryId(?string $lastCloseDateChangedHistoryId): self
    {
        $this->lastCloseDateChangedHistoryId = $lastCloseDateChangedHistoryId;

        return $this;
    }

    public function setAccount(?array $account): self
    {
        if (!is_null($account)) {
            $class = $this->virtualProperties['Account']['class'];
            $model = $class::convert($account);
        }
        else $model = $account;

        $this->virtualProperties['Account']['value'] = $model;

        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $modelClass = $this->virtualProperties['Attributes']['class'];
        $this->virtualProperties['Attributes']['value'] = $modelClass::convert($attributes);

        return $this;
    }
}