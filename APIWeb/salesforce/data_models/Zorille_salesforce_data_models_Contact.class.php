<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

/**
 * @method Account getAccount()
 * @method Contact setAccount(Account $account)
 *
 * @method static Contact create()
 */
class Contact extends data_model
{
    const ENTITY_NAME = 'Contact';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ],
        'Account' => [
            'class' => Account::class,
            'value' => null
        ],
    ];

    protected ?string $id = null;
    protected ?string $accountId = null;
    protected ?string $assistantName = null;
    protected ?string $assistantPhone = null;
    protected ?string $birthdate = null;
    protected ?string $currencyIsoCode = null;
    protected ?string $ownerId = null;
    protected ?string $createdById = null;
    protected ?string $jigsaw = null;
    protected ?string $department = null;
    protected ?string $description = null;
    protected ?string $doNotCall = null;
    protected ?string $email = null;
    protected bool $hasOptedOutOfEmail = false;
    protected ?string $fax = null;
    protected bool $hasOptedOutOfFax = false;
    protected ?string $genderIdentity = null;
    protected ?string $homePhone = null;
    protected ?string $lastCURequestDate = null;
    protected ?string $leadSource = null;
    /** @var string|Address|null $mailingAddress */
    protected $mailingAddress = null;
    protected ?string $mobilePhone = null;
    protected ?string $name = null;
    protected ?string $lastname = null;
    protected ?string $firstname = null;
    /** @var string|Address|null $otherAddress */
    protected $otherAddress = null;
    protected ?string $otherPhone = null;
    protected ?string $phone = null;
    protected ?string $pronouns = null;
    protected ?string $reportsToId = null;
    protected ?string $title = null;
    protected bool $isDeleted = false;
    protected ?string $masterRecordId = null;
    protected ?string $salutation = null;
    protected ?string $middleName = null;
    protected ?string $suffix = null;
    protected ?string $otherStreet = null;
    protected ?string $otherCity = null;
    protected ?string $otherState = null;
    protected ?string $otherPostalCode = null;
    protected ?string $otherCountry = null;
    protected ?float $otherLatitude = null;
    protected ?float $otherLongitude = null;
    protected ?string $otherGeocodeAccuracy = null;
    protected ?string $mailingStreet = null;
    protected ?string $mailingCity = null;
    protected ?string $mailingState = null;
    protected ?string $mailingPostalCode = null;
    protected ?string $mailingCountry = null;
    protected ?string $mailingGeocodeAccuracy = null;
    protected ?float $mailingLongitude = null;
    protected ?float $mailingLatitude = null;
    protected ?string $createdDate = null;
    protected ?string $lastModifiedDate = null;
    protected ?string $lastModifiedById = null;
    protected ?string $systemModstamp = null;
    protected ?string $lastActivityDate = null;
    protected ?string $lastCUUpdateDate = null;
    protected ?string $lastViewedDate = null;
    protected ?string $lastReferencedDate = null;
    protected ?string $emailBouncedReason = null;
    protected ?string $emailBouncedDate = null;
    protected bool $isEmailBounced = false;
    protected ?string $photoUrl = null;
    protected ?string $jigsawContactId = null;
    protected bool $isPriorityRecord = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

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

    public function getAssistantName(): ?string
    {
        return $this->assistantName;
    }

    public function setAssistantName(?string $assistantName): self
    {
        $this->assistantName = $assistantName;

        return $this;
    }

    public function getAssistantPhone(): ?string
    {
        return $this->assistantPhone;
    }

    public function setAssistantPhone(?string $assistantPhone): self
    {
        $this->assistantPhone = $assistantPhone;

        return $this;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(?string $birthdate): self
    {
        $this->birthdate = $birthdate;

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

    public function getCreatedById(): ?string
    {
        return $this->createdById;
    }

    public function setCreatedById(?string $createdById): self
    {
        $this->createdById = $createdById;

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

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

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

    public function getDoNotCall(): ?string
    {
        return $this->doNotCall;
    }

    public function setDoNotCall(?string $doNotCall): self
    {
        $this->doNotCall = $doNotCall;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isHasOptedOutOfEmail(): bool
    {
        return $this->hasOptedOutOfEmail;
    }

    public function setHasOptedOutOfEmail(bool $hasOptedOutOfEmail): self
    {
        $this->hasOptedOutOfEmail = $hasOptedOutOfEmail;

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

    public function isHasOptedOutOfFax(): bool
    {
        return $this->hasOptedOutOfFax;
    }

    public function setHasOptedOutOfFax(bool $hasOptedOutOfFax): self
    {
        $this->hasOptedOutOfFax = $hasOptedOutOfFax;

        return $this;
    }

    public function getGenderIdentity(): ?string
    {
        return $this->genderIdentity;
    }

    public function setGenderIdentity(?string $genderIdentity): self
    {
        $this->genderIdentity = $genderIdentity;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): self
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    public function getLastCURequestDate(): ?string
    {
        return $this->lastCURequestDate;
    }

    public function setLastCURequestDate(?string $lastCURequestDate): self
    {
        $this->lastCURequestDate = $lastCURequestDate;

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

    /**
     * @return Address|string|null
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * @param Address|string|null $mailingAddress
     */
    public function setMailingAddress($mailingAddress): self
    {
        $this->mailingAddress = $mailingAddress;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

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

    /**
     * @return Address|string|null
     */
    public function getOtherAddress()
    {
        return $this->otherAddress;
    }

    /**
     * @param Address|string|null $otherAddress
     */
    public function setOtherAddress($otherAddress): self
    {
        $this->otherAddress = $otherAddress;

        return $this;
    }

    public function getOtherPhone(): ?string
    {
        return $this->otherPhone;
    }

    public function setOtherPhone(?string $otherPhone): self
    {
        $this->otherPhone = $otherPhone;

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

    public function getPronouns(): ?string
    {
        return $this->pronouns;
    }

    public function setPronouns(?string $pronouns): self
    {
        $this->pronouns = $pronouns;

        return $this;
    }

    public function getReportsToId(): ?string
    {
        return $this->reportsToId;
    }

    public function setReportsToId(?string $reportsToId): self
    {
        $this->reportsToId = $reportsToId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
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

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    public function setSalutation(?string $salutation): self
    {
        $this->salutation = $salutation;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(?string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function getOtherStreet(): ?string
    {
        return $this->otherStreet;
    }

    public function setOtherStreet(?string $otherStreet): self
    {
        $this->otherStreet = $otherStreet;

        return $this;
    }

    public function getOtherCity(): ?string
    {
        return $this->otherCity;
    }

    public function setOtherCity(?string $otherCity): self
    {
        $this->otherCity = $otherCity;

        return $this;
    }

    public function getOtherState(): ?string
    {
        return $this->otherState;
    }

    public function setOtherState(?string $otherState): self
    {
        $this->otherState = $otherState;

        return $this;
    }

    public function getOtherPostalCode(): ?string
    {
        return $this->otherPostalCode;
    }

    public function setOtherPostalCode(?string $otherPostalCode): self
    {
        $this->otherPostalCode = $otherPostalCode;

        return $this;
    }

    public function getOtherCountry(): ?string
    {
        return $this->otherCountry;
    }

    public function setOtherCountry(?string $otherCountry): self
    {
        $this->otherCountry = $otherCountry;

        return $this;
    }

    public function getOtherLatitude(): ?float
    {
        return $this->otherLatitude;
    }

    public function setOtherLatitude(?float $otherLatitude): self
    {
        $this->otherLatitude = $otherLatitude;

        return $this;
    }

    public function getOtherLongitude(): ?float
    {
        return $this->otherLongitude;
    }

    public function setOtherLongitude(?float $otherLongitude): self
    {
        $this->otherLongitude = $otherLongitude;

        return $this;
    }

    public function getOtherGeocodeAccuracy(): ?string
    {
        return $this->otherGeocodeAccuracy;
    }

    public function setOtherGeocodeAccuracy(?string $otherGeocodeAccuracy): self
    {
        $this->otherGeocodeAccuracy = $otherGeocodeAccuracy;

        return $this;
    }

    public function getMailingStreet(): ?string
    {
        return $this->mailingStreet;
    }

    public function setMailingStreet(?string $mailingStreet): self
    {
        $this->mailingStreet = $mailingStreet;

        return $this;
    }

    public function getMailingCity(): ?string
    {
        return $this->mailingCity;
    }

    public function setMailingCity(?string $mailingCity): self
    {
        $this->mailingCity = $mailingCity;

        return $this;
    }

    public function getMailingState(): ?string
    {
        return $this->mailingState;
    }

    public function setMailingState(?string $mailingState): self
    {
        $this->mailingState = $mailingState;

        return $this;
    }

    public function getMailingPostalCode(): ?string
    {
        return $this->mailingPostalCode;
    }

    public function setMailingPostalCode(?string $mailingPostalCode): self
    {
        $this->mailingPostalCode = $mailingPostalCode;

        return $this;
    }

    public function getMailingCountry(): ?string
    {
        return $this->mailingCountry;
    }

    public function setMailingCountry(?string $mailingCountry): self
    {
        $this->mailingCountry = $mailingCountry;

        return $this;
    }

    public function getMailingLatitude(): ?float
    {
        return $this->mailingLatitude;
    }

    public function setMailingLatitude(?float $mailingLatitude): self
    {
        $this->mailingLatitude = $mailingLatitude;

        return $this;
    }

    public function getMailingLongitude(): ?float
    {
        return $this->mailingLongitude;
    }

    public function setMailingLongitude(?float $mailingLongitude): self
    {
        $this->mailingLongitude = $mailingLongitude;

        return $this;
    }

    public function getMailingGeocodeAccuracy(): ?string
    {
        return $this->mailingGeocodeAccuracy;
    }

    public function setMailingGeocodeAccuracy(?string $mailingGeocodeAccuracy): self
    {
        $this->mailingGeocodeAccuracy = $mailingGeocodeAccuracy;

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

    public function getLastCUUpdateDate(): ?string
    {
        return $this->lastCUUpdateDate;
    }

    public function setLastCUUpdateDate(?string $lastCUUpdateDate): self
    {
        $this->lastCUUpdateDate = $lastCUUpdateDate;

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

    public function getEmailBouncedReason(): ?string
    {
        return $this->emailBouncedReason;
    }

    public function setEmailBouncedReason(?string $emailBouncedReason): self
    {
        $this->emailBouncedReason = $emailBouncedReason;

        return $this;
    }

    public function getEmailBouncedDate(): ?string
    {
        return $this->emailBouncedDate;
    }

    public function setEmailBouncedDate(?string $emailBouncedDate): self
    {
        $this->emailBouncedDate = $emailBouncedDate;

        return $this;
    }

    public function isEmailBounced(): bool
    {
        return $this->isEmailBounced;
    }

    public function setIsEmailBounced(bool $isEmailBounced): self
    {
        $this->isEmailBounced = $isEmailBounced;

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

    public function getJigsawContactId(): ?string
    {
        return $this->jigsawContactId;
    }

    public function setJigsawContactId(?string $jigsawContactId): self
    {
        $this->jigsawContactId = $jigsawContactId;

        return $this;
    }

    public function isPriorityRecord(): bool
    {
        return $this->isPriorityRecord;
    }

    public function setIsPriorityRecord(bool $isPriorityRecord): self
    {
        $this->isPriorityRecord = $isPriorityRecord;

        return $this;
    }
}