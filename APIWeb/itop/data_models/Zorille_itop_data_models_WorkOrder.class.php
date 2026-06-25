<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class WorkOrder extends data_model
{
    const ENTITY_NAME = 'WorkOrder';

    protected ?string $id = null;
    protected string $finalclass = 'WorkOrder';
    protected ?string $name = null;
    protected ?string $status = null;
    protected ?string $description = null;
    protected ?string $ticket_id = null;
    protected ?string $ticket_ref = null;
    protected ?string $team_id = null;
    protected ?string $team_name = null;
    protected ?string $agent_id = null;
    protected ?string $agent_email = null;
    protected ?string $start_date = null;
    protected ?string $end_date = null;
    protected ?string $log = null;
    protected ?string $nonworkinghour_flag = null;
    protected ?string $deplacement_flag = null;
    protected ?string $time_spent = null;
    protected ?string $friendlyname = null;
    protected ?string $ticket_id_friendlyname = null;
    protected ?string $ticket_id_finalclass_recall = null;
    protected ?string $ticket_id_archive_flag = null;
    protected ?string $team_id_friendlyname = null;
    protected ?string $team_id_obsolescence_flag = null;
    protected ?string $agent_id_friendlyname = null;
    protected ?string $agent_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return WorkOrder
     */
    public function setId(?string $id): WorkOrder
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    /**
     * @param string $finalclass
     * @return WorkOrder
     */
    public function setFinalclass(string $finalclass): WorkOrder
    {
        $this->finalclass = $finalclass;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return WorkOrder
     */
    public function setName(?string $name): WorkOrder
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return WorkOrder
     */
    public function setStatus(?string $status): WorkOrder
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return WorkOrder
     */
    public function setDescription(?string $description): WorkOrder
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketId(): ?string
    {
        return $this->ticket_id;
    }

    /**
     * @param string|null $ticket_id
     * @return WorkOrder
     */
    public function setTicketId(?string $ticket_id): WorkOrder
    {
        $this->ticket_id = $ticket_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketRef(): ?string
    {
        return $this->ticket_ref;
    }

    /**
     * @param string|null $ticket_ref
     * @return WorkOrder
     */
    public function setTicketRef(?string $ticket_ref): WorkOrder
    {
        $this->ticket_ref = $ticket_ref;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    /**
     * @param string|null $team_id
     * @return WorkOrder
     */
    public function setTeamId(?string $team_id): WorkOrder
    {
        $this->team_id = $team_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTeamName(): ?string
    {
        return $this->team_name;
    }

    /**
     * @param string|null $team_name
     * @return WorkOrder
     */
    public function setTeamName(?string $team_name): WorkOrder
    {
        $this->team_name = $team_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAgentId(): ?string
    {
        return $this->agent_id;
    }

    /**
     * @param string|null $agent_id
     * @return WorkOrder
     */
    public function setAgentId(?string $agent_id): WorkOrder
    {
        $this->agent_id = $agent_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAgentEmail(): ?string
    {
        return $this->agent_email;
    }

    /**
     * @param string|null $agent_email
     * @return WorkOrder
     */
    public function setAgentEmail(?string $agent_email): WorkOrder
    {
        $this->agent_email = $agent_email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    /**
     * @param string|null $start_date
     * @return WorkOrder
     */
    public function setStartDate(?string $start_date): WorkOrder
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    /**
     * @param string|null $end_date
     * @return WorkOrder
     */
    public function setEndDate(?string $end_date): WorkOrder
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLog(): ?string
    {
        return $this->log;
    }

    /**
     * @param string|null $log
     * @return WorkOrder
     */
    public function setLog(?string $log): WorkOrder
    {
        $this->log = $log;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNonworkinghourFlag(): ?string
    {
        return $this->nonworkinghour_flag;
    }

    /**
     * @param string|null $nonworkinghour_flag
     * @return WorkOrder
     */
    public function setNonworkinghourFlag(?string $nonworkinghour_flag): WorkOrder
    {
        $this->nonworkinghour_flag = $nonworkinghour_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeplacementFlag(): ?string
    {
        return $this->deplacement_flag;
    }

    /**
     * @param string|null $deplacement_flag
     * @return WorkOrder
     */
    public function setDeplacementFlag(?string $deplacement_flag): WorkOrder
    {
        $this->deplacement_flag = $deplacement_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimeSpent(): ?string
    {
        return $this->time_spent;
    }

    /**
     * @param string|null $time_spent
     * @return WorkOrder
     */
    public function setTimeSpent(?string $time_spent): WorkOrder
    {
        $this->time_spent = $time_spent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    /**
     * @param string|null $friendlyname
     * @return WorkOrder
     */
    public function setFriendlyname(?string $friendlyname): WorkOrder
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketIdFriendlyname(): ?string
    {
        return $this->ticket_id_friendlyname;
    }

    /**
     * @param string|null $ticket_id_friendlyname
     * @return WorkOrder
     */
    public function setTicketIdFriendlyname(?string $ticket_id_friendlyname): WorkOrder
    {
        $this->ticket_id_friendlyname = $ticket_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketIdFinalclassRecall(): ?string
    {
        return $this->ticket_id_finalclass_recall;
    }

    /**
     * @param string|null $ticket_id_finalclass_recall
     * @return WorkOrder
     */
    public function setTicketIdFinalclassRecall(?string $ticket_id_finalclass_recall): WorkOrder
    {
        $this->ticket_id_finalclass_recall = $ticket_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketIdArchiveFlag(): ?string
    {
        return $this->ticket_id_archive_flag;
    }

    /**
     * @param string|null $ticket_id_archive_flag
     * @return WorkOrder
     */
    public function setTicketIdArchiveFlag(?string $ticket_id_archive_flag): WorkOrder
    {
        $this->ticket_id_archive_flag = $ticket_id_archive_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTeamIdFriendlyname(): ?string
    {
        return $this->team_id_friendlyname;
    }

    /**
     * @param string|null $team_id_friendlyname
     * @return WorkOrder
     */
    public function setTeamIdFriendlyname(?string $team_id_friendlyname): WorkOrder
    {
        $this->team_id_friendlyname = $team_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTeamIdObsolescenceFlag(): ?string
    {
        return $this->team_id_obsolescence_flag;
    }

    /**
     * @param string|null $team_id_obsolescence_flag
     * @return WorkOrder
     */
    public function setTeamIdObsolescenceFlag(?string $team_id_obsolescence_flag): WorkOrder
    {
        $this->team_id_obsolescence_flag = $team_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAgentIdFriendlyname(): ?string
    {
        return $this->agent_id_friendlyname;
    }

    /**
     * @param string|null $agent_id_friendlyname
     * @return WorkOrder
     */
    public function setAgentIdFriendlyname(?string $agent_id_friendlyname): WorkOrder
    {
        $this->agent_id_friendlyname = $agent_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAgentIdObsolescenceFlag(): ?string
    {
        return $this->agent_id_obsolescence_flag;
    }

    /**
     * @param string|null $agent_id_obsolescence_flag
     * @return WorkOrder
     */
    public function setAgentIdObsolescenceFlag(?string $agent_id_obsolescence_flag): WorkOrder
    {
        $this->agent_id_obsolescence_flag = $agent_id_obsolescence_flag;
        return $this;
    }
}