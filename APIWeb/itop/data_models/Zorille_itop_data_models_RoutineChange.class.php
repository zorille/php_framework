<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class RoutineChange extends data_model
{
    const ENTITY_NAME = 'RoutineChange';

    protected ?string $id = null;
    protected ?string $operational_status = null;
    protected ?string $ref = null;
    protected ?string $org_id = null;
    protected ?string $org_name = null;
    protected ?string $caller_id = null;
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
    protected ?string $private_log = null;
    protected array $contacts_list = [];
    protected array $functionalcis_list = [];
    protected array $workorders_list = [];
    protected ?string $related_project_id = null;
    protected ?string $related_project_ref = null;
    protected array $stockelements_list = [];
    protected array $StockElement_list = [];
    protected ?string $location_id = null;
    protected ?string $status = null;
    protected ?string $reason = null;
    protected ?string $requestor_id = null;
    protected ?string $requestor_email = null;
    protected ?string $creation_date = null;
    protected ?string $impact = null;
    protected ?string $supervisor_group_id = null;
    protected ?string $supervisor_group_name = null;
    protected ?string $supervisor_id = null;
    protected ?string $supervisor_email = null;
    protected ?string $manager_group_id = null;
    protected ?string $manager_group_name = null;
    protected ?string $manager_id = null;
    protected ?string $manager_email = null;
    protected ?string $outage = null;
    protected ?string $fallback = null;
    protected ?string $parent_id = null;
    protected ?string $parent_name = null;
    protected array $related_request_list = [];
    protected array $related_incident_list = [];
    protected array $child_changes_list = [];
    protected ?string $friendlyname = null;
    protected ?string $archive_flag = null;
    protected ?string $archive_date = null;
    protected ?string $org_id_friendlyname = null;
    protected ?string $org_id_obsolescence_flag = null;
    protected ?string $caller_id_friendlyname = null;
    protected ?string $caller_id_obsolescence_flag = null;
    protected ?string $team_id_friendlyname = null;
    protected ?string $team_id_obsolescence_flag = null;
    protected ?string $agent_id_friendlyname = null;
    protected ?string $agent_id_obsolescence_flag = null;
    protected ?string $related_project_id_friendlyname = null;
    protected ?string $related_project_id_archive_flag = null;
    protected ?string $location_id_friendlyname = null;
    protected ?string $location_id_obsolescence_flag = null;
    protected ?string $requestor_id_friendlyname = null;
    protected ?string $requestor_id_obsolescence_flag = null;
    protected ?string $supervisor_group_id_friendlyname = null;
    protected ?string $supervisor_group_id_obsolescence_flag = null;
    protected ?string $supervisor_id_friendlyname = null;
    protected ?string $supervisor_id_obsolescence_flag = null;
    protected ?string $manager_group_id_friendlyname = null;
    protected ?string $manager_group_id_obsolescence_flag = null;
    protected ?string $manager_id_friendlyname = null;
    protected ?string $manager_id_obsolescence_flag = null;
    protected ?string $parent_id_friendlyname = null;
    protected ?string $parent_id_finalclass_recall = null;
    protected ?string $parent_id_archive_flag = null;
    
    protected string $finalclass = 'RoutineChange';

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getOperationalStatus(): ?string
    {
        return $this->operational_status;
    }

    public function setOperationalStatus(?string $operational_status): static
    {
        $this->operational_status = $operational_status;
        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): static
    {
        $this->ref = $ref;
        return $this;
    }

    public function getOrgId(): ?string
    {
        return $this->org_id;
    }

    public function setOrgId(?string $org_id): static
    {
        $this->org_id = $org_id;
        return $this;
    }

    public function getOrgName(): ?string
    {
        return $this->org_name;
    }

    public function setOrgName(?string $org_name): static
    {
        $this->org_name = $org_name;
        return $this;
    }

    public function getCallerId(): ?string
    {
        return $this->caller_id;
    }

    public function setCallerId(?string $caller_id): static
    {
        $this->caller_id = $caller_id;
        return $this;
    }

    public function getCallerName(): ?string
    {
        return $this->caller_name;
    }

    public function setCallerName(?string $caller_name): static
    {
        $this->caller_name = $caller_name;
        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    public function setTeamId(?string $team_id): static
    {
        $this->team_id = $team_id;
        return $this;
    }

    public function getTeamName(): ?string
    {
        return $this->team_name;
    }

    public function setTeamName(?string $team_name): static
    {
        $this->team_name = $team_name;
        return $this;
    }

    public function getAgentId(): ?string
    {
        return $this->agent_id;
    }

    public function setAgentId(?string $agent_id): static
    {
        $this->agent_id = $agent_id;
        return $this;
    }

    public function getAgentName(): ?string
    {
        return $this->agent_name;
    }

    public function setAgentName(?string $agent_name): static
    {
        $this->agent_name = $agent_name;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function setStartDate(?string $start_date): static
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    public function setEndDate(?string $end_date): static
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->last_update;
    }

    public function setLastUpdate(?string $last_update): static
    {
        $this->last_update = $last_update;
        return $this;
    }

    public function getCloseDate(): ?string
    {
        return $this->close_date;
    }

    public function setCloseDate(?string $close_date): static
    {
        $this->close_date = $close_date;
        return $this;
    }

    public function getPrivateLog(): ?string
    {
        return $this->private_log;
    }

    public function setPrivateLog(?string $private_log): static
    {
        $this->private_log = $private_log;
        return $this;
    }

    public function getContactsList(): array
    {
        return $this->contacts_list;
    }

    public function setContactsList(array $contacts_list): static
    {
        $this->contacts_list = $contacts_list;
        return $this;
    }

    public function getFunctionalcisList(): array
    {
        return $this->functionalcis_list;
    }

    public function setFunctionalcisList(array $functionalcis_list): static
    {
        $this->functionalcis_list = $functionalcis_list;
        return $this;
    }

    public function getWorkordersList(): array
    {
        return $this->workorders_list;
    }

    public function setWorkordersList(array $workorders_list): static
    {
        $this->workorders_list = $workorders_list;
        return $this;
    }

    public function getRelatedProjectId(): ?string
    {
        return $this->related_project_id;
    }

    public function setRelatedProjectId(?string $related_project_id): static
    {
        $this->related_project_id = $related_project_id;
        return $this;
    }

    public function getRelatedProjectRef(): ?string
    {
        return $this->related_project_ref;
    }

    public function setRelatedProjectRef(?string $related_project_ref): static
    {
        $this->related_project_ref = $related_project_ref;
        return $this;
    }

    public function getStockelementsList(): array
    {
        return $this->stockelements_list;
    }

    public function setStockelementsList(array $stockelements_list): static
    {
        $this->stockelements_list = $stockelements_list;
        return $this;
    }

    public function getStockElementList(): array
    {
        return $this->StockElement_list;
    }

    public function setStockElementList(array $StockElement_list): static
    {
        $this->StockElement_list = $StockElement_list;
        return $this;
    }

    public function getLocationId(): ?string
    {
        return $this->location_id;
    }

    public function setLocationId(?string $location_id): static
    {
        $this->location_id = $location_id;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;
        return $this;
    }

    public function getRequestorId(): ?string
    {
        return $this->requestor_id;
    }

    public function setRequestorId(?string $requestor_id): static
    {
        $this->requestor_id = $requestor_id;
        return $this;
    }

    public function getRequestorEmail(): ?string
    {
        return $this->requestor_email;
    }

    public function setRequestorEmail(?string $requestor_email): static
    {
        $this->requestor_email = $requestor_email;
        return $this;
    }

    public function getCreationDate(): ?string
    {
        return $this->creation_date;
    }

    public function setCreationDate(?string $creation_date): static
    {
        $this->creation_date = $creation_date;
        return $this;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): static
    {
        $this->impact = $impact;
        return $this;
    }

    public function getSupervisorGroupId(): ?string
    {
        return $this->supervisor_group_id;
    }

    public function setSupervisorGroupId(?string $supervisor_group_id): static
    {
        $this->supervisor_group_id = $supervisor_group_id;
        return $this;
    }

    public function getSupervisorGroupName(): ?string
    {
        return $this->supervisor_group_name;
    }

    public function setSupervisorGroupName(?string $supervisor_group_name): static
    {
        $this->supervisor_group_name = $supervisor_group_name;
        return $this;
    }

    public function getSupervisorId(): ?string
    {
        return $this->supervisor_id;
    }

    public function setSupervisorId(?string $supervisor_id): static
    {
        $this->supervisor_id = $supervisor_id;
        return $this;
    }

    public function getSupervisorEmail(): ?string
    {
        return $this->supervisor_email;
    }

    public function setSupervisorEmail(?string $supervisor_email): static
    {
        $this->supervisor_email = $supervisor_email;
        return $this;
    }

    public function getManagerGroupId(): ?string
    {
        return $this->manager_group_id;
    }

    public function setManagerGroupId(?string $manager_group_id): static
    {
        $this->manager_group_id = $manager_group_id;
        return $this;
    }

    public function getManagerGroupName(): ?string
    {
        return $this->manager_group_name;
    }

    public function setManagerGroupName(?string $manager_group_name): static
    {
        $this->manager_group_name = $manager_group_name;
        return $this;
    }

    public function getManagerId(): ?string
    {
        return $this->manager_id;
    }

    public function setManagerId(?string $manager_id): static
    {
        $this->manager_id = $manager_id;
        return $this;
    }

    public function getManagerEmail(): ?string
    {
        return $this->manager_email;
    }

    public function setManagerEmail(?string $manager_email): static
    {
        $this->manager_email = $manager_email;
        return $this;
    }

    public function getOutage(): ?string
    {
        return $this->outage;
    }

    public function setOutage(?string $outage): static
    {
        $this->outage = $outage;
        return $this;
    }

    public function getFallback(): ?string
    {
        return $this->fallback;
    }

    public function setFallback(?string $fallback): static
    {
        $this->fallback = $fallback;
        return $this;
    }

    public function getParentId(): ?string
    {
        return $this->parent_id;
    }

    public function setParentId(?string $parent_id): static
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    public function getParentName(): ?string
    {
        return $this->parent_name;
    }

    public function setParentName(?string $parent_name): static
    {
        $this->parent_name = $parent_name;
        return $this;
    }

    public function getRelatedRequestList(): array
    {
        return $this->related_request_list;
    }

    public function setRelatedRequestList(array $related_request_list): static
    {
        $this->related_request_list = $related_request_list;
        return $this;
    }

    public function getRelatedIncidentList(): array
    {
        return $this->related_incident_list;
    }

    public function setRelatedIncidentList(array $related_incident_list): static
    {
        $this->related_incident_list = $related_incident_list;
        return $this;
    }

    public function getChildChangesList(): array
    {
        return $this->child_changes_list;
    }

    public function setChildChangesList(array $child_changes_list): static
    {
        $this->child_changes_list = $child_changes_list;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    public function setFriendlyname(?string $friendlyname): static
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getArchiveFlag(): ?string
    {
        return $this->archive_flag;
    }

    public function setArchiveFlag(?string $archive_flag): static
    {
        $this->archive_flag = $archive_flag;
        return $this;
    }

    public function getArchiveDate(): ?string
    {
        return $this->archive_date;
    }

    public function setArchiveDate(?string $archive_date): static
    {
        $this->archive_date = $archive_date;
        return $this;
    }

    public function getOrgIdFriendlyname(): ?string
    {
        return $this->org_id_friendlyname;
    }

    public function setOrgIdFriendlyname(?string $org_id_friendlyname): static
    {
        $this->org_id_friendlyname = $org_id_friendlyname;
        return $this;
    }

    public function getOrgIdObsolescenceFlag(): ?string
    {
        return $this->org_id_obsolescence_flag;
    }

    public function setOrgIdObsolescenceFlag(?string $org_id_obsolescence_flag): static
    {
        $this->org_id_obsolescence_flag = $org_id_obsolescence_flag;
        return $this;
    }

    public function getCallerIdFriendlyname(): ?string
    {
        return $this->caller_id_friendlyname;
    }

    public function setCallerIdFriendlyname(?string $caller_id_friendlyname): static
    {
        $this->caller_id_friendlyname = $caller_id_friendlyname;
        return $this;
    }

    public function getCallerIdObsolescenceFlag(): ?string
    {
        return $this->caller_id_obsolescence_flag;
    }

    public function setCallerIdObsolescenceFlag(?string $caller_id_obsolescence_flag): static
    {
        $this->caller_id_obsolescence_flag = $caller_id_obsolescence_flag;
        return $this;
    }

    public function getTeamIdFriendlyname(): ?string
    {
        return $this->team_id_friendlyname;
    }

    public function setTeamIdFriendlyname(?string $team_id_friendlyname): static
    {
        $this->team_id_friendlyname = $team_id_friendlyname;
        return $this;
    }

    public function getTeamIdObsolescenceFlag(): ?string
    {
        return $this->team_id_obsolescence_flag;
    }

    public function setTeamIdObsolescenceFlag(?string $team_id_obsolescence_flag): static
    {
        $this->team_id_obsolescence_flag = $team_id_obsolescence_flag;
        return $this;
    }

    public function getAgentIdFriendlyname(): ?string
    {
        return $this->agent_id_friendlyname;
    }

    public function setAgentIdFriendlyname(?string $agent_id_friendlyname): static
    {
        $this->agent_id_friendlyname = $agent_id_friendlyname;
        return $this;
    }

    public function getAgentIdObsolescenceFlag(): ?string
    {
        return $this->agent_id_obsolescence_flag;
    }

    public function setAgentIdObsolescenceFlag(?string $agent_id_obsolescence_flag): static
    {
        $this->agent_id_obsolescence_flag = $agent_id_obsolescence_flag;
        return $this;
    }

    public function getRelatedProjectIdFriendlyname(): ?string
    {
        return $this->related_project_id_friendlyname;
    }

    public function setRelatedProjectIdFriendlyname(?string $related_project_id_friendlyname): static
    {
        $this->related_project_id_friendlyname = $related_project_id_friendlyname;
        return $this;
    }

    public function getRelatedProjectIdArchiveFlag(): ?string
    {
        return $this->related_project_id_archive_flag;
    }

    public function setRelatedProjectIdArchiveFlag(?string $related_project_id_archive_flag): static
    {
        $this->related_project_id_archive_flag = $related_project_id_archive_flag;
        return $this;
    }

    public function getLocationIdFriendlyname(): ?string
    {
        return $this->location_id_friendlyname;
    }

    public function setLocationIdFriendlyname(?string $location_id_friendlyname): static
    {
        $this->location_id_friendlyname = $location_id_friendlyname;
        return $this;
    }

    public function getLocationIdObsolescenceFlag(): ?string
    {
        return $this->location_id_obsolescence_flag;
    }

    public function setLocationIdObsolescenceFlag(?string $location_id_obsolescence_flag): static
    {
        $this->location_id_obsolescence_flag = $location_id_obsolescence_flag;
        return $this;
    }

    public function getRequestorIdFriendlyname(): ?string
    {
        return $this->requestor_id_friendlyname;
    }

    public function setRequestorIdFriendlyname(?string $requestor_id_friendlyname): static
    {
        $this->requestor_id_friendlyname = $requestor_id_friendlyname;
        return $this;
    }

    public function getRequestorIdObsolescenceFlag(): ?string
    {
        return $this->requestor_id_obsolescence_flag;
    }

    public function setRequestorIdObsolescenceFlag(?string $requestor_id_obsolescence_flag): static
    {
        $this->requestor_id_obsolescence_flag = $requestor_id_obsolescence_flag;
        return $this;
    }

    public function getSupervisorGroupIdFriendlyname(): ?string
    {
        return $this->supervisor_group_id_friendlyname;
    }

    public function setSupervisorGroupIdFriendlyname(?string $supervisor_group_id_friendlyname): static
    {
        $this->supervisor_group_id_friendlyname = $supervisor_group_id_friendlyname;
        return $this;
    }

    public function getSupervisorGroupIdObsolescenceFlag(): ?string
    {
        return $this->supervisor_group_id_obsolescence_flag;
    }

    public function setSupervisorGroupIdObsolescenceFlag(?string $supervisor_group_id_obsolescence_flag): static
    {
        $this->supervisor_group_id_obsolescence_flag = $supervisor_group_id_obsolescence_flag;
        return $this;
    }

    public function getSupervisorIdFriendlyname(): ?string
    {
        return $this->supervisor_id_friendlyname;
    }

    public function setSupervisorIdFriendlyname(?string $supervisor_id_friendlyname): static
    {
        $this->supervisor_id_friendlyname = $supervisor_id_friendlyname;
        return $this;
    }

    public function getSupervisorIdObsolescenceFlag(): ?string
    {
        return $this->supervisor_id_obsolescence_flag;
    }

    public function setSupervisorIdObsolescenceFlag(?string $supervisor_id_obsolescence_flag): static
    {
        $this->supervisor_id_obsolescence_flag = $supervisor_id_obsolescence_flag;
        return $this;
    }

    public function getManagerGroupIdFriendlyname(): ?string
    {
        return $this->manager_group_id_friendlyname;
    }

    public function setManagerGroupIdFriendlyname(?string $manager_group_id_friendlyname): static
    {
        $this->manager_group_id_friendlyname = $manager_group_id_friendlyname;
        return $this;
    }

    public function getManagerGroupIdObsolescenceFlag(): ?string
    {
        return $this->manager_group_id_obsolescence_flag;
    }

    public function setManagerGroupIdObsolescenceFlag(?string $manager_group_id_obsolescence_flag): static
    {
        $this->manager_group_id_obsolescence_flag = $manager_group_id_obsolescence_flag;
        return $this;
    }

    public function getManagerIdFriendlyname(): ?string
    {
        return $this->manager_id_friendlyname;
    }

    public function setManagerIdFriendlyname(?string $manager_id_friendlyname): static
    {
        $this->manager_id_friendlyname = $manager_id_friendlyname;
        return $this;
    }

    public function getManagerIdObsolescenceFlag(): ?string
    {
        return $this->manager_id_obsolescence_flag;
    }

    public function setManagerIdObsolescenceFlag(?string $manager_id_obsolescence_flag): static
    {
        $this->manager_id_obsolescence_flag = $manager_id_obsolescence_flag;
        return $this;
    }

    public function getParentIdFriendlyname(): ?string
    {
        return $this->parent_id_friendlyname;
    }

    public function setParentIdFriendlyname(?string $parent_id_friendlyname): static
    {
        $this->parent_id_friendlyname = $parent_id_friendlyname;
        return $this;
    }

    public function getParentIdFinalclassRecall(): ?string
    {
        return $this->parent_id_finalclass_recall;
    }

    public function setParentIdFinalclassRecall(?string $parent_id_finalclass_recall): static
    {
        $this->parent_id_finalclass_recall = $parent_id_finalclass_recall;
        return $this;
    }

    public function getParentIdArchiveFlag(): ?string
    {
        return $this->parent_id_archive_flag;
    }

    public function setParentIdArchiveFlag(?string $parent_id_archive_flag): static
    {
        $this->parent_id_archive_flag = $parent_id_archive_flag;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): static
    {
        $this->finalclass = $finalclass;
        return $this;
    }
}