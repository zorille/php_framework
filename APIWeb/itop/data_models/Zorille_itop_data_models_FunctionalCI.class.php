<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

/**
 * @method static self create()
 */
class FunctionalCI extends data_model
{
    const ENTITY_NAME = 'FunctionalCI';

    protected ?int $id = null;
    protected ?string $name = null;
    protected ?string $description = null;
    protected ?string $friendlyname = null;
    protected ?string $org_id = null;
    protected ?string $organization_name = null;
    protected CriticityEnum $business_criticity = CriticityEnum::LOW;
    protected ?string $move2production = null;
    protected array $contacts_list = [];
    protected array $documents_list = [];
    protected array $applicationsolution_list = [];
    protected array $softwares_list = [];
    protected array $providercontracts_list = [];
    protected array $services_list = [];
    protected array $tickets_list = [];
    protected array $accesspermissions_list = [];
    protected ?string $overview_list = null;
    protected BinaryEnum $needmonitoring = BinaryEnum::YES;
    protected array $backups_list = [];
    protected ?string $uniq_name = null;
    protected array $crmassetss_list = [];
    protected string $finalclass = 'FunctionalCI';
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

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(?string $friendlyname): self
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getOrgId(): ?string
    {
        return $this->org_id;
    }
    public function setOrgId(?string $org_id): self
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrganizationName(): ?string
    {
        return $this->organization_name;
    }
    public function setOrganizationName(?string $organization_name): self
    {
        $this->organization_name = $organization_name;
        return $this;
    }

    public function getBusinessCriticity(): ?CriticityEnum
    {
        return $this->business_criticity;
    }
    public function setBusinessCriticity(CriticityEnum|string|null $business_criticity): self
    {
        if (is_string($business_criticity)) {
            $business_criticity = CriticityEnum::from($business_criticity);
        }
        $this->business_criticity = $business_criticity;
        return $this;
    }

    public function getMove2production(): ?string
    {
        return $this->move2production;
    }
    public function setMove2production(?string $move2production): self
    {
        $this->move2production = $move2production;
        return $this;
    }

    public function getContactsList(): array
    {
        return $this->contacts_list;
    }
    public function setContactsList(array $contacts_list): self
    {
        $this->contacts_list = $contacts_list;
        return $this;
    }

    public function getDocumentsList(): array
    {
        return $this->documents_list;
    }
    public function setDocumentsList(array $documents_list): self
    {
        $this->documents_list = $documents_list;
        return $this;
    }

    public function getApplicationsolutionList(): array
    {
        return $this->applicationsolution_list;
    }
    public function setApplicationsolutionList(array $applicationsolution_list): self
    {
        $this->applicationsolution_list = $applicationsolution_list;
        return $this;
    }

    public function getSoftwaresList(): array
    {
        return $this->softwares_list;
    }
    public function setSoftwaresList(array $softwares_list): self
    {
        $this->softwares_list = $softwares_list;
        return $this;
    }

    public function getProvidercontractsList(): array
    {
        return $this->providercontracts_list;
    }
    public function setProvidercontractsList(array $providercontracts_list): self
    {
        $this->providercontracts_list = $providercontracts_list;
        return $this;
    }

    public function getServicesList(): array
    {
        return $this->services_list;
    }
    public function setServicesList(array $services_list): self
    {
        $this->services_list = $services_list;
        return $this;
    }

    public function getTicketsList(): array
    {
        return $this->tickets_list;
    }
    public function setTicketsList(array $tickets_list): self
    {
        $this->tickets_list = $tickets_list;
        return $this;
    }

    public function getAccesspermissionsList(): array
    {
        return $this->accesspermissions_list;
    }
    public function setAccesspermissionsList(array $accesspermissions_list): self
    {
        $this->accesspermissions_list = $accesspermissions_list;
        return $this;
    }

    public function getOverviewList(): ?string
    {
        return $this->overview_list;
    }
    public function setOverviewList(?string $overview_list): self
    {
        $this->overview_list = $overview_list;
        return $this;
    }

    public function getNeedmonitoring(): BinaryEnum
    {
        return $this->needmonitoring;
    }
    public function setNeedmonitoring(string|BinaryEnum $needmonitoring): self
    {
        if (is_string($needmonitoring)) {
            $needmonitoring = BinaryEnum::from($needmonitoring);
        }
        $this->needmonitoring = $needmonitoring;
        return $this;
    }

    public function getBackupsList(): array
    {
        return $this->backups_list;
    }
    public function setBackupsList(array $backups_list): self
    {
        $this->backups_list = $backups_list;
        return $this;
    }

    public function getUniqName(): ?string
    {
        return $this->uniq_name;
    }
    public function setUniqName(?string $uniq_name): self
    {
        $this->uniq_name = $uniq_name;
        return $this;
    }

    public function getCrmassetssList(): array
    {
        return $this->crmassetss_list;
    }
    public function setCrmassetssList(array $crmassetss_list): self
    {
        $this->crmassetss_list = $crmassetss_list;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }
    public function setFinalclass(string $finalclass): self
    {
        $this->finalclass = $finalclass;
        return $this;
    }

    public function getObsolescenceDate(): ?string
    {
        return $this->obsolescence_date;
    }
    public function setObsolescenceDate(?string $obsolescence_date): self
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }
    public function setOrgIdFriendlyname(?string $org_id_friendlyname): self
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }
    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): self
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }
}