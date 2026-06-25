<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

/**
 * @method static self create()
 */
class Ticket extends data_model
{
    const ENTITY_NAME = 'Ticket';

    const OPERATIONAL_STATUS_ON_GOING = 'ongoing';
    const OPERATIONAL_STATUS_RESOLVED = 'resolved';
    const OPERATIONAL_STATUS_CLOSED = 'closed';

    protected ?string $operational_status = self::OPERATIONAL_STATUS_ON_GOING;
    protected ?string $ref = null;
    protected ?string $org_id = null;
    protected ?string $org_name = null;
    protected ?string $caller_id = null;
    protected ?string $caller_email = null;
    protected ?string $caller_name = null;
    protected ?string $team_id = null;
    protected ?string $team_name = null;
    protected ?string $agent_id = null;
    protected ?string $agent_name = null;
    protected ?string $title = null;
    protected ?string $description = null;
    protected ?string $start_date = null;
    protected ?string $end_date = null;
    protected ?string $last_update = null;
    protected ?string $close_date = null;
    protected array $private_log = [];
    protected array $contacts_list = [];
    /** @var FunctionalCI[] $functionalcis_list */
    protected array $functionalcis_list = [];
    protected array $workorders_list = [];
    protected ?string $related_project_id = null;
    protected ?string $related_project_ref = null;
    protected array $StockElement_list = [];
    protected string $finalclass = 'Ticket';
    protected ?string $friendlyname = null;
    protected ?string $archive_flag = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $team_id_friendlyname = null;
    protected ?string $team_id_obsolescence_flag = null;
    protected ?string $agent_id_friendlyname = null;
    protected ?string $agent_id_obsolescence_flag = null;
    protected ?string $related_project_id_friendlyname = null;
    protected ?string $related_project_id_archive_flag = null;
    protected ?string $location_id = null;

    public function getOperationalStatus(): ?string
    {
        return $this->operational_status;
    }
    public function setOperationalStatus(?string $operational_status): self
    {
        $this->operational_status = $operational_status;
        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }
    public function setRef(?string $ref): self
    {
        $this->ref = $ref;
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

    public function getOrgName(): ?string
    {
        return $this->org_name;
    }
    public function setOrgName(?string $org_name): self
    {
        $this->org_name = $org_name;
        return $this;
    }

    public function getCallerId(): ?string
    {
        return $this->caller_id;
    }
    public function setCallerId(?string $caller_id): self
    {
        $this->caller_id = $caller_id;
        return $this;
    }

    public function getCallerEmail(): ?string
    {
        return $this->caller_email;
    }
    public function setCallerEmail(?string $caller_email): self
    {
        $this->caller_email = $caller_email;
        return $this;
    }

    public function getCallerName(): ?string
    {
        return $this->caller_name;
    }
    public function setCallerName(?string $caller_name): self
    {
        $this->caller_name = $caller_name;
        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }
    public function setTeamId(?string $team_id): self
    {
        $this->team_id = $team_id;
        return $this;
    }

    public function getTeamName(): ?string
    {
        return $this->team_name;
    }
    public function setTeamName(?string $team_name): self
    {
        $this->team_name = $team_name;
        return $this;
    }

    public function getAgentId(): ?string
    {
        return $this->agent_id;
    }
    public function setAgentId(?string $agent_id): self
    {
        $this->agent_id = $agent_id;
        return $this;
    }

    public function getAgentName(): ?string
    {
        return $this->agent_name;
    }
    public function setAgentName(?string $agent_name): self
    {
        $this->agent_name = $agent_name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }
    public function setStartDate(?string $start_date): self
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }
    public function setEndDate(?string $end_date): self
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->last_update;
    }
    public function setLastUpdate(?string $last_update): self
    {
        $this->last_update = $last_update;
        return $this;
    }

    public function getCloseDate(): ?string
    {
        return $this->close_date;
    }
    public function setCloseDate(?string $close_date): self
    {
        $this->close_date = $close_date;
        return $this;
    }

    public function getPrivateLog(): array
    {
        return $this->private_log;
    }
    public function setPrivateLog(array $private_log): self
    {
        $this->private_log = $private_log;
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

    public function getFunctionalcisList(): array
    {
        return $this->functionalcis_list;
    }
    public function setFunctionalcisList(array $functionalcis_list): self
    {
        $this->functionalcis_list = $functionalcis_list;
        return $this;
    }

    public function getWorkordersList(): array
    {
        return $this->workorders_list;
    }
    public function setWorkordersList(array $workorders_list): self
    {
        $this->workorders_list = $workorders_list;
        return $this;
    }

    public function getRelatedProjectId(): ?string
    {
        return $this->related_project_id;
    }
    public function setRelatedProjectId(?string $related_project_id): self
    {
        $this->related_project_id = $related_project_id;
        return $this;
    }

    public function getRelatedProjectRef(): ?string
    {
        return $this->related_project_ref;
    }
    public function setRelatedProjectRef(?string $related_project_ref): self
    {
        $this->related_project_ref = $related_project_ref;
        return $this;
    }

    public function getStockElementList(): array
    {
        return $this->StockElement_list;
    }
    public function setStockElementList(array $StockElement_list): self
    {
        $this->StockElement_list = $StockElement_list;
        return $this;
    }

    public function getFinalclass(): ?string
    {
        return $this->finalclass;
    }
    public function setFinalclass(?string $finalclass): self
    {
        $this->finalclass = $finalclass;
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

    public function getArchiveFlag(): ?string
    {
        return $this->archive_flag;
    }
    public function setArchiveFlag(?string $archive_flag): self
    {
        $this->archive_flag = $archive_flag;
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

    public function getTeamIdFriendlyname(): ?string
    {
        return $this->team_id_friendlyname;
    }
    public function setTeamIdFriendlyname(?string $team_id_friendlyname): self
    {
        $this->team_id_friendlyname = $team_id_friendlyname;
        return $this;
    }

    public function getTeamIdObsolescenceFlag(): ?string
    {
        return $this->team_id_obsolescence_flag;
    }
    public function setTeamIdObsolescenceFlag(?string $team_id_obsolescence_flag): self
    {
        $this->team_id_obsolescence_flag = $team_id_obsolescence_flag;
        return $this;
    }

    public function getAgentIdFriendlyname(): ?string
    {
        return $this->agent_id_friendlyname;
    }
    public function setAgentIdFriendlyname(?string $agent_id_friendlyname): self
    {
        $this->agent_id_friendlyname = $agent_id_friendlyname;
        return $this;
    }

    public function getAgentIdObsolescenceFlag(): ?string
    {
        return $this->agent_id_obsolescence_flag;
    }
    public function setAgentIdObsolescenceFlag(?string $agent_id_obsolescence_flag): self
    {
        $this->agent_id_obsolescence_flag = $agent_id_obsolescence_flag;
        return $this;
    }

    public function getRelatedProjectIdFriendlyname(): ?string
    {
        return $this->related_project_id_friendlyname;
    }
    public function setRelatedProjectIdFriendlyname(?string $related_project_id_friendlyname): self
    {
        $this->related_project_id_friendlyname = $related_project_id_friendlyname;
        return $this;
    }

    public function getRelatedProjectIdArchiveFlag(): ?string
    {
        return $this->related_project_id_archive_flag;
    }
    public function setRelatedProjectIdArchiveFlag(?string $related_project_id_archive_flag): self
    {
        $this->related_project_id_archive_flag = $related_project_id_archive_flag;
        return $this;
    }

    public function getLocationId(): ?string
    {
        return $this->location_id;
    }
    public function setLocationId(?string $location_id): self
    {
        $this->location_id = $location_id;
        return $this;
    }
}