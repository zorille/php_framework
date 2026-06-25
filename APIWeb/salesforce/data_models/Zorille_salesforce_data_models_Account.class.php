<?php

namespace Zorille\salesforce\data_models;

use Exception;
use Zorille\framework\abstract_log;
use Zorille\salesforce\data_model;
use Zorille\framework\QueryBuilderOperator as QLOperator;
use Zorille\salesforce\SalesforceFactory;

/**
 * @method Attributes[] getAttributes()
 */
class Account extends data_model
{
    const ENTITY_NAME = 'Account';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ]
    ];

    private ?string $id = "0016800000VS1FzAAL";
    private ?bool $isDeleted = false;
    private ?string $masterRecordId = null;
    private ?string $name = "Deutsche Post E-POST Solutions GmbH";
    private ?string $type = "Customer";
    private ?string $parentId = null;
    private ?string $billingStreet = null;
    private ?string $billingCity = null;
    private ?string $billingState = null;
    private ?string $billingPostalCode = null;
    private ?string $billingCountry = null;
    private ?float $billingLatitude = null;
    private ?float $billingLongitude = null;
    private ?string $billingGeocodeAccuracy = null;
    /** @var string|array|Address|null $billingAddress */
    private $billingAddress = null;
    private ?string $shippingStreet = "";
    private ?string $shippingCity = "Bonn";
    private ?string $shippingState = null;
    private ?string $shippingPostalCode = "53119";
    private ?string $shippingCountry = "Germany";
    private ?float $shippingLatitude = null;
    private ?float $shippingLongitude = null;
    private ?string $shippingGeocodeAccuracy = null;
    private ?Address $shippingAddress = null;
    private ?string $phone = null;
    private ?string $fax = null;
    private ?string $accountNumber = null;
    private ?string $website = "https://www.deutschepost.de/de/e/epost-solutions.html";
    private ?string $photoUrl = "/services/images/photo/0016800000VS1FzAAL";
    private ?string $sic = null;
    private ?string $industry = "Shipping";
    private ?string $annualRevenue = null;
    private ?int $numberOfEmployees = null;
    private ?string $ownership = null;
    private ?string $tickerSymbol = null;
    private ?string $description = null;
    private ?string $rating = null;
    private ?string $site = null;
    private ?string $currencyIsoCode = "EUR";
    private ?string $ownerId = "00568000000Oqk9AAC";
    private ?string $createdDate = "2023-06-08T08:31:09.000;+0000";
    private ?string $createdById = "005680000048zZVAAY";
    private ?string $lastModifiedDate = "2024-02-29T22:36:02.000;+0000";
    private ?string $lastModifiedById = "00568000004AQLuAAO";
    private ?string $systemModstamp = "2024-02-29T22:36:02.000;+0000";
    private ?string $lastActivityDate = null;
    private ?string $lastViewedDate = null;
    private ?string $lastReferencedDate = null;
    private ?bool $isPartner = false;
    private ?string $channelProgramName = null;
    private ?string $channelProgramLevelName = null;
    private ?string $jigsaw = null;
    private ?string $jigsawCompanyId = null;
    private ?string $accountSource = null;
    private ?string $sicDesc = null;
    private ?string $customer_Number_Text__c = null;

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

    public function getMasterRecordId(): ?string
    {
        return $this->masterRecordId;
    }

    public function setMasterRecordId(?string $masterRecordId): self
    {
        $this->masterRecordId = $masterRecordId;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getBillingStreet(): ?string
    {
        return $this->billingStreet;
    }

    public function setBillingStreet(?string $billingStreet): self
    {
        $this->billingStreet = $billingStreet;

        return $this;
    }

    public function getBillingCity(): ?string
    {
        return $this->billingCity;
    }

    public function setBillingCity(?string $billingCity): self
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    public function getBillingState(): ?string
    {
        return $this->billingState;
    }

    public function setBillingState(?string $billingState): self
    {
        $this->billingState = $billingState;

        return $this;
    }

    public function getBillingPostalCode(): ?string
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode(?string $billingPostalCode): self
    {
        $this->billingPostalCode = $billingPostalCode;

        return $this;
    }

    public function getBillingCountry(): ?string
    {
        return $this->billingCountry;
    }

    public function setBillingCountry(?string $billingCountry): self
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    public function getBillingLatitude(): ?float
    {
        return $this->billingLatitude;
    }

    public function setBillingLatitude(?float $billingLatitude): self
    {
        $this->billingLatitude = $billingLatitude;

        return $this;
    }

    public function getBillingLongitude(): ?float
    {
        return $this->billingLongitude;
    }

    public function setBillingLongitude(?float $billingLongitude): self
    {
        $this->billingLongitude = $billingLongitude;

        return $this;
    }

    public function getBillingGeocodeAccuracy(): ?string
    {
        return $this->billingGeocodeAccuracy;
    }

    public function setBillingGeocodeAccuracy(?string $billingGeocodeAccuracy): self
    {
        $this->billingGeocodeAccuracy = $billingGeocodeAccuracy;

        return $this;
    }

    /**
     * @return Address|string|array|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param string|array|Address|null $billingAddress
     * @return self
     */
    public function setBillingAddress($billingAddress): self
    {
        if (is_array($billingAddress)) {
            $this->billingAddress = Address::convert($billingAddress);
        }
        else {
            $this->billingAddress = $billingAddress;
        }

        return $this;
    }

    public function getShippingStreet(): ?string
    {
        return $this->shippingStreet;
    }

    public function setShippingStreet(?string $shippingStreet): self
    {
        $this->shippingStreet = $shippingStreet;

        return $this;
    }

    public function getShippingCity(): ?string
    {
        return $this->shippingCity;
    }

    public function setShippingCity(?string $shippingCity): self
    {
        $this->shippingCity = $shippingCity;

        return $this;
    }

    public function getShippingState(): ?string
    {
        return $this->shippingState;
    }

    public function setShippingState(?string $shippingState): self
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    public function getShippingPostalCode(): ?string
    {
        return $this->shippingPostalCode;
    }

    public function setShippingPostalCode(?string $shippingPostalCode): self
    {
        $this->shippingPostalCode = $shippingPostalCode;

        return $this;
    }

    public function getShippingCountry(): ?string
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry(?string $shippingCountry): self
    {
        $this->shippingCountry = $shippingCountry;

        return $this;
    }

    public function getShippingLatitude(): ?float
    {
        return $this->shippingLatitude;
    }

    public function setShippingLatitude(?float $shippingLatitude): self
    {
        $this->shippingLatitude = $shippingLatitude;

        return $this;
    }

    public function getShippingLongitude(): ?float
    {
        return $this->shippingLongitude;
    }

    public function setShippingLongitude(?float $shippingLongitude): self
    {
        $this->shippingLongitude = $shippingLongitude;

        return $this;
    }

    public function getShippingGeocodeAccuracy(): ?string
    {
        return $this->shippingGeocodeAccuracy;
    }

    public function setShippingGeocodeAccuracy(?string $shippingGeocodeAccuracy): self
    {
        $this->shippingGeocodeAccuracy = $shippingGeocodeAccuracy;

        return $this;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?array $shippingAddress): self
    {
        if (is_null($shippingAddress)) {
            $model = null;
        }
        else {
            /** @var Address $model */
            $model = Address::convert($shippingAddress);
        }
        $this->shippingAddress = $model;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getSic(): ?string
    {
        return $this->sic;
    }

    public function setSic(?string $sic): self
    {
        $this->sic = $sic;

        return $this;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    public function getAnnualRevenue(): ?string
    {
        return $this->annualRevenue;
    }

    public function setAnnualRevenue(?string $annualRevenue): self
    {
        $this->annualRevenue = $annualRevenue;

        return $this;
    }

    public function getNumberOfEmployees(): ?int
    {
        return $this->numberOfEmployees;
    }

    public function setNumberOfEmployees(?int $numberOfEmployees): self
    {
        $this->numberOfEmployees = $numberOfEmployees;

        return $this;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function setOwnership(?string $ownership): self
    {
        $this->ownership = $ownership;

        return $this;
    }

    public function getTickerSymbol(): ?string
    {
        return $this->tickerSymbol;
    }

    public function setTickerSymbol(?string $tickerSymbol): self
    {
        $this->tickerSymbol = $tickerSymbol;

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

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): self
    {
        $this->site = $site;

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

    public function isPartner(): ?bool
    {
        return $this->isPartner;
    }

    public function setIsPartner(?bool $isPartner): self
    {
        $this->isPartner = $isPartner;

        return $this;
    }

    public function getChannelProgramName(): ?string
    {
        return $this->channelProgramName;
    }

    public function setChannelProgramName(?string $channelProgramName): self
    {
        $this->channelProgramName = $channelProgramName;

        return $this;
    }

    public function getChannelProgramLevelName(): ?string
    {
        return $this->channelProgramLevelName;
    }

    public function setChannelProgramLevelName(?string $channelProgramLevelName): self
    {
        $this->channelProgramLevelName = $channelProgramLevelName;

        return $this;
    }

    public function getJigsaw(): ?string
    {
        return $this->jigsaw;
    }

    public function setJigsaw(?string $jigsaw): self
    {
        $this->jigsaw = $jigsaw;

        return $this;
    }

    public function getJigsawCompanyId(): ?string
    {
        return $this->jigsawCompanyId;
    }

    public function setJigsawCompanyId(?string $jigsawCompanyId): self
    {
        $this->jigsawCompanyId = $jigsawCompanyId;

        return $this;
    }

    public function getAccountSource(): ?string
    {
        return $this->accountSource;
    }

    public function setAccountSource(?string $accountSource): self
    {
        $this->accountSource = $accountSource;

        return $this;
    }

    public function getSicDesc(): ?string
    {
        return $this->sicDesc;
    }

    public function setSicDesc(?string $sicDesc): self
    {
        $this->sicDesc = $sicDesc;

        return $this;
    }

    public function getCustomerNumberTextC(): ?string
    {
        return $this->customer_Number_Text__c;
    }

    public function setCustomerNumberTextC(?string $customer_Number_Text__c): self
    {
        $this->customer_Number_Text__c = $customer_Number_Text__c;

        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $modelClass = $this->virtualProperties['Attributes']['class'];
        $this->virtualProperties['Attributes']['value'] = $modelClass::convert($attributes);

        return $this;
    }

    /**
     * @return Contact[]
     * @throws Exception
     */
    public function getContactList(): array
    {
        if (empty($this->getSalesforceServeur())) {
            abstract_log::onError_standard('On a besoin de salesforce_serveur pour travailler');
            return [];
        }

        return SalesforceFactory::new()->createContactQueryBuilder()
            ->select()
            ->where('IsDeleted', QLOperator::EQUALS, false)
            ->and()
            ->where('AccountId', QLOperator::EQUALS, $this->getId())
            ->build()->toModel()['records'];
    }
}