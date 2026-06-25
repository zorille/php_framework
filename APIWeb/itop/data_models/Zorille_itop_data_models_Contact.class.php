<?php

namespace Zorille\itop\data_models;

use Exception;
use Zorille\itop\data_model;
use Zorille\itop\ItopFactory;
use Zorille\framework\QueryBuilderOperator as QLOperator;

class Contact extends data_model
{
    const ENTITY_NAME = 'Contact';

    protected array $virtualProperties = [
        'Organization' => [
            'class' => Organization::class,
            'value' => null,
        ]
    ];

    protected int $id = -1;
    protected string $name = '';
    protected StatusEnum $status = StatusEnum::ACTIVE;
    protected string $org_id = '';
    protected ?string $org_name = null;
    protected ?string $email = null;
    protected ?string $phone = null;
    protected ?BinaryEnum $notify = BinaryEnum::YES;
    protected ?string $function = null;
    protected array $cis_list = [];
    protected array $emailaliass_list = [];
    protected array $wbss_list = [];
    protected LanguageEnum $language = LanguageEnum::US;
    protected string $finalclass = 'Contact';
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $obsolescence_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }
    public function setStatus(StatusEnum|string $status): Contact
    {
		if (is_string($status)) {
			$status = StatusEnum::from($status);
		}
        $this->status = $status;
        return $this;
    }

    public function getOrgId(): string
    {
        return $this->org_id;
    }
    public function setOrgId(string $org_id): Contact
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrgName(): ?string
    {
        return $this->org_name;
    }
    public function setOrgName(?string $org_name): Contact
    {
        $this->org_name = $org_name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(?string $phone): Contact
    {
        $this->phone = $phone;
        return $this;
    }

    public function getNotify(): ?BinaryEnum
    {
        return $this->notify;
    }
    public function setNotify(BinaryEnum|string $notify): Contact
    {
	    if (is_string($notify)) {
		    $notify = BinaryEnum::from($notify);
	    }
        $this->notify = $notify;
        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }
    public function setFunction(?string $function): Contact
    {
        $this->function = $function;
        return $this;
    }

    public function getCisList(): array
    {
        return $this->cis_list;
    }
    public function setCisList(array $cis_list): Contact
    {
        $this->cis_list = $cis_list;
        return $this;
    }

    public function getEmailaliassList(): array
    {
        return $this->emailaliass_list;
    }
    public function setEmailaliassList(array $emailaliass_list): Contact
    {
        $this->emailaliass_list = $emailaliass_list;
        return $this;
    }

    public function getWbssList(): array
    {
        return $this->wbss_list;
    }
    public function setWbssList(array $wbss_list): Contact
    {
        $this->wbss_list = $wbss_list;
        return $this;
    }

    public function getLanguage(): LanguageEnum
    {
        return $this->language;
    }
    public function setLanguage(LanguageEnum|string $language): Contact
    {
		if (is_string($language)) {
			$language = LanguageEnum::from($language);
		}
        $this->language = $language;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }
    public function setFinalclass(string $finalclass): Contact
    {
        $this->finalclass = $finalclass;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(?string $friendlyname): Contact
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }
    public function setObsolescenceFlag(?string $obsolescence_flag): Contact
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getObsolescenceDate(): ?string
    {
        return $this->obsolescence_date;
    }
    public function setObsolescenceDate(?string $obsolescence_date): Contact
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): Contact
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): Contact
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getOrganization(): Organization
    {
        return [...array_values(ItopFactory::new()->createOrganizationQueryBuilder()
            ->select('id', 'friendlyname')
            ->where('id', QLOperator::EQUALS, $this->getOrgId())
            ->build()
            ->toModel()['objects'])][0];
    }
}