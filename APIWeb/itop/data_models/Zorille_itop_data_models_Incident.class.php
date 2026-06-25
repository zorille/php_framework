<?php

namespace Zorille\itop\data_models;

class Incident extends Ticket
{
    const ENTITY_NAME = 'Incident';

    const STATUS_NEW = 'new';
    const STATUS_DISPATCHED = 'dispatched';
	const STATUS_REDISPATCHED = 'redispatched';
	const STATUS_ASSIGNED = 'assigned';
	const STATUS_PENDING = 'pending';
	const STATUS_ESCALATED_TTO = 'escalated_tto';
	const STATUS_ESCALATED_TTR = 'escalated_ttr';
	const STATUS_RESOLVED = 'resolved';
	const STATUS_CLOSED = 'closed';

    const IMPACT_DEPARTEMENT = '1';
	const IMPACT_SERVICE = '2';
	const IMPACT_PERSONNE = '3';

    const URGENCE_CRITIQUE = '1';
	const URGENCE_HAUTE = '2';
	const URGENCE_MOYENNE = '3';
	const URGENCE_BASSE = '4';

    const ORIGIN_CHAT = 'chat';
	const ORIGIN_MAIL = 'mail';
	const ORIGIN_IN_PERSON = 'in_person';
	const ORIGIN_PORTAL = 'portal';
	const ORIGIN_MONITORING = 'monitoring';
	const ORIGIN_PHONE = 'phone';

    const BINARY_RESPONSE_NO = 'no';
	const BINARY_RESPONSE_YES = 'yes';

    protected ?string $id = null;
    protected string $status = self::STATUS_NEW;
    protected string $impact = self::IMPACT_DEPARTEMENT;
    protected string $priority = self::URGENCE_BASSE;
    protected string $urgency = self::URGENCE_BASSE;
    protected string $origin = self::ORIGIN_PHONE;
    protected ?string $service_id = null;
    protected ?string $service_name = null;
    protected ?string $servicesubcategory_id = null;
    protected ?string $servicesubcategory_name = null;
    protected ?string $escalation_flag = self::BINARY_RESPONSE_NO;
    protected ?string $escalation_reason = null;
    protected ?string $assignment_date = null;
    protected ?string $resolution_date = null;
    protected string $finalclass = 'Incident';

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getImpact(): string
    {
        return $this->impact;
    }
    public function setImpact(string $impact): self
    {
        $this->impact = $impact;
        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }
    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getUrgency(): string
    {
        return $this->urgency;
    }
    public function setUrgency(string $urgency): self
    {
        $this->urgency = $urgency;
        return $this;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }
    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;
        return $this;
    }

    public function getServiceId(): ?string
    {
        return $this->service_id;
    }
    public function setServiceId(?string $service_id): self
    {
        $this->service_id = $service_id;
        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }
    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;
        return $this;
    }

    public function getServicesubcategoryId(): ?string
    {
        return $this->servicesubcategory_id;
    }
    public function setServicesubcategoryId(?string $servicesubcategory_id): self
    {
        $this->servicesubcategory_id = $servicesubcategory_id;
        return $this;
    }

    public function getServicesubcategoryName(): ?string
    {
        return $this->servicesubcategory_name;
    }
    public function setServicesubcategoryName(?string $servicesubcategory_name): self
    {
        $this->servicesubcategory_name = $servicesubcategory_name;
        return $this;
    }

    public function getEscalationFlag(): ?string
    {
        return $this->escalation_flag;
    }
    public function setEscalationFlag(?string $escalation_flag): self
    {
        $this->escalation_flag = $escalation_flag;
        return $this;
    }

    public function getEscalationReason(): ?string
    {
        return $this->escalation_reason;
    }
    public function setEscalationReason(?string $escalation_reason): self
    {
        $this->escalation_reason = $escalation_reason;
        return $this;
    }

    public function getAssignmentDate(): ?string
    {
        return $this->assignment_date;
    }
    public function setAssignmentDate(?string $assignment_date): self
    {
        $this->assignment_date = $assignment_date;
        return $this;
    }

    public function getResolutionDate(): ?string
    {
        return $this->resolution_date;
    }
    public function setResolutionDate(?string $resolution_date): self
    {
        $this->resolution_date = $resolution_date;
        return $this;
    }
}