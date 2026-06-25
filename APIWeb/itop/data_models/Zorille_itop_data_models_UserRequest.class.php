<?php

namespace Zorille\itop\data_models;

/**
 * @method static self create()
 */
class UserRequest extends Ticket
{
    const ENTITY_NAME = 'UserRequest';

    const STATUS_APPROVED = 'approved';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_CLOSED = 'closed';
    const STATUS_DISPATCHED = 'dispatched';
    const STATUS_ESCALATED_TTO = 'escalated_tto';
    const STATUS_ESCALATED_TTR = 'escalated_ttr';
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_REDISPATCHED = 'redispatched';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    const SERVICE_REQUEST = 'service_request';

    const IMPACT_DEPARTMENT = 1;
    const IMPACT_SERVICE = 2;
    const IMPACT_PERSON = 3;

    const CRITICAL = 1;
    const HIGH = 2;
    const MIDDLE = 3;
    const LOW = 4;

    const ORIGIN_EMAIL = 'mail';
    const ORIGIN_PHONE = 'phone';
    const ORIGIN_PORTAL = 'portal';

    const ASSISTANCE = 'assistance';
    const BUG_FIX = 'bug fix';
    const HARDWARE_REPAIR = 'hardware repair';
    const OTHER = 'other';
    const SOFTWARE_PATCH = 'software patch';
    const SYSTEM_UPDATE = 'system update';
    const TRAINING = 'training';

    const PERFECT_USER_SATISFACTION = 1;
    const HIGH_USER_SATISFACTION = 2;
    const MIDDLE_USER_SATISFACTION = 3;
    const LOW_USER_SATISFACTION = 4;

    const YES = 'yes';
    const NO = 'no';

    private ?string $id = null;
    private string $status = self::STATUS_NEW;
    private string $request_type = self::SERVICE_REQUEST;
    private int $impact = self::IMPACT_DEPARTMENT;
    private int $priority = self::LOW;
    private int $urgency = self::LOW;
    private ?string $origin = self::ORIGIN_PHONE;
    private ?string $service_id = null;
    private ?string $service_name = null;
    private ?string $servicesubcategory_id = null;
    private ?string $servicesubcategory_name = null;
    private string $escalation_flag = self::NO;
    private ?string $escalation_reason = null;
    private ?string $assignment_date = null;
    private ?string $resolution_date = null;
    private ?string $last_pending_date = null;
    private ?int $cumulatedpending = null;
    private ?int $tto = null;
    private ?int $ttr = null;
    private ?string $tto_escalation_deadline = null;
    private bool $sla_tto_passed = false;
    private ?string $sla_tto_over = null;
    private ?string $ttr_escalation_deadline = null;
    private bool $sla_ttr_passed = false;
    private ?string $sla_ttr_over = null;
    private ?string $time_spent = null;
    private string $resolution_code = self::ASSISTANCE;
    private ?string $solution = null;
    private ?string $pending_reason = null;
    private ?string $parent_request_id = null;
    private ?string $parent_request_ref = null;
    private ?string $parent_incident_id = null;
    private ?string $parent_incident_ref = null;
    private ?string $parent_change_id = null;
    private ?string $parent_change_ref = null;
    private array $related_request_list = [];
    private array $public_log = [];
    private int $user_satisfaction = self::PERFECT_USER_SATISFACTION;
    private ?string $user_comment = null;
    private ?string $service_details = null;
    protected string $finalclass = 'UserRequest';
    private ?string $service_id_friendlyname = null;
    private ?string $servicesubcategory_id_friendlyname = null;
    private ?string $parent_request_id_friendlyname = null;
    private ?string $parent_request_id_archive_flag = null;
    private ?string $parent_incident_id_friendlyname = null;
    private ?string $parent_incident_id_archive_flag = null;
    private ?string $parent_change_id_friendlyname = null;
    private ?string $parent_change_id_finalclass_recall = null;
    private ?string $parent_change_id_archive_flag = null;
    private ?string $due_date = null;

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

    public function getRequestType(): string
    {
        return $this->request_type;
    }
    public function setRequestType(string $request_type): self
    {
        $this->request_type = $request_type;
        return $this;
    }

    public function getImpact(): int
    {
        return $this->impact;
    }
    public function setImpact(int $impact): self
    {
        $this->impact = $impact;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getUrgency(): int
    {
        return $this->urgency;
    }
    public function setUrgency(int $urgency): self
    {
        $this->urgency = $urgency;
        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }
    public function setOrigin(?string $origin): self
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

    public function getEscalationFlag(): string
    {
        return $this->escalation_flag;
    }
    public function setEscalationFlag(string $escalation_flag): self
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

    public function getLastPendingDate(): ?string
    {
        return $this->last_pending_date;
    }
    public function setLastPendingDate(?string $last_pending_date): self
    {
        $this->last_pending_date = $last_pending_date;
        return $this;
    }

    public function getCumulatedpending(): ?int
    {
        return $this->cumulatedpending;
    }
    public function setCumulatedpending(?int $cumulatedpending): self
    {
        $this->cumulatedpending = $cumulatedpending;
        return $this;
    }

    public function getTto(): ?int
    {
        return $this->tto;
    }
    public function setTto(?int $tto): self
    {
        $this->tto = $tto;
        return $this;
    }

    public function getTtr(): ?int
    {
        return $this->ttr;
    }
    public function setTtr(?int $ttr): self
    {
        $this->ttr = $ttr;
        return $this;
    }

    public function getTtoEscalationDeadline(): ?string
    {
        return $this->tto_escalation_deadline;
    }
    public function setTtoEscalationDeadline(?string $tto_escalation_deadline): self
    {
        $this->tto_escalation_deadline = $tto_escalation_deadline;
        return $this;
    }

    public function isSlaTtoPassed(): bool
    {
        return $this->sla_tto_passed;
    }
    public function setSlaTtoPassed(bool $sla_tto_passed): self
    {
        $this->sla_tto_passed = $sla_tto_passed;
        return $this;
    }

    public function getSlaTtoOver(): ?string
    {
        return $this->sla_tto_over;
    }
    public function setSlaTtoOver(?string $sla_tto_over): self
    {
        $this->sla_tto_over = $sla_tto_over;
        return $this;
    }

    public function getTtrEscalationDeadline(): ?string
    {
        return $this->ttr_escalation_deadline;
    }
    public function setTtrEscalationDeadline(?string $ttr_escalation_deadline): self
    {
        $this->ttr_escalation_deadline = $ttr_escalation_deadline;
        return $this;
    }

    public function isSlaTtrPassed(): bool
    {
        return $this->sla_ttr_passed;
    }
    public function setSlaTtrPassed(bool $sla_ttr_passed): self
    {
        $this->sla_ttr_passed = $sla_ttr_passed;
        return $this;
    }

    public function getSlaTtrOver(): ?string
    {
        return $this->sla_ttr_over;
    }
    public function setSlaTtrOver(?string $sla_ttr_over): self
    {
        $this->sla_ttr_over = $sla_ttr_over;
        return $this;
    }

    public function getTimeSpent(): ?string
    {
        return $this->time_spent;
    }
    public function setTimeSpent(?string $time_spent): self
    {
        $this->time_spent = $time_spent;
        return $this;
    }

    public function getResolutionCode(): string
    {
        return $this->resolution_code;
    }
    public function setResolutionCode(string $resolution_code): self
    {
        $this->resolution_code = $resolution_code;
        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }
    public function setSolution(?string $solution): self
    {
        $this->solution = $solution;
        return $this;
    }

    public function getPendingReason(): ?string
    {
        return $this->pending_reason;
    }
    public function setPendingReason(?string $pending_reason): self
    {
        $this->pending_reason = $pending_reason;
        return $this;
    }

    public function getParentRequestId(): ?string
    {
        return $this->parent_request_id;
    }
    public function setParentRequestId(?string $parent_request_id): self
    {
        $this->parent_request_id = $parent_request_id;
        return $this;
    }

    public function getParentRequestRef(): ?string
    {
        return $this->parent_request_ref;
    }
    public function setParentRequestRef(?string $parent_request_ref): self
    {
        $this->parent_request_ref = $parent_request_ref;
        return $this;
    }

    public function getParentIncidentId(): ?string
    {
        return $this->parent_incident_id;
    }
    public function setParentIncidentId(?string $parent_incident_id): self
    {
        $this->parent_incident_id = $parent_incident_id;
        return $this;
    }

    public function getParentIncidentRef(): ?string
    {
        return $this->parent_incident_ref;
    }
    public function setParentIncidentRef(?string $parent_incident_ref): self
    {
        $this->parent_incident_ref = $parent_incident_ref;
        return $this;
    }

    public function getParentChangeId(): ?string
    {
        return $this->parent_change_id;
    }
    public function setParentChangeId(?string $parent_change_id): self
    {
        $this->parent_change_id = $parent_change_id;
        return $this;
    }

    public function getParentChangeRef(): ?string
    {
        return $this->parent_change_ref;
    }
    public function setParentChangeRef(?string $parent_change_ref): self
    {
        $this->parent_change_ref = $parent_change_ref;
        return $this;
    }

    public function getRelatedRequestList(): array
    {
        return $this->related_request_list;
    }
    public function setRelatedRequestList(array $related_request_list): self
    {
        $this->related_request_list = $related_request_list;
        return $this;
    }

    public function getPublicLog(): array
    {
        return $this->public_log;
    }
    public function setPublicLog(array $public_log): self
    {
        $this->public_log = $public_log;
        return $this;
    }

    public function getUserSatisfaction(): int
    {
        return $this->user_satisfaction;
    }
    public function setUserSatisfaction(int $user_satisfaction): self
    {
        $this->user_satisfaction = $user_satisfaction;
        return $this;
    }

    public function getUserComment(): ?string
    {
        return $this->user_comment;
    }
    public function setUserComment(?string $user_comment): self
    {
        $this->user_comment = $user_comment;
        return $this;
    }

    public function getServiceDetails(): ?string
    {
        return $this->service_details;
    }
    public function setServiceDetails(?string $service_details): self
    {
        $this->service_details = $service_details;
        return $this;
    }

    public function getServiceIdFriendlyname(): ?string
    {
        return $this->service_id_friendlyname;
    }
    public function setServiceIdFriendlyname(?string $service_id_friendlyname): self
    {
        $this->service_id_friendlyname = $service_id_friendlyname;
        return $this;
    }

    public function getServicesubcategoryIdFriendlyname(): ?string
    {
        return $this->servicesubcategory_id_friendlyname;
    }
    public function setServicesubcategoryIdFriendlyname(?string $servicesubcategory_id_friendlyname): self
    {
        $this->servicesubcategory_id_friendlyname = $servicesubcategory_id_friendlyname;
        return $this;
    }

    public function getParentRequestIdFriendlyname(): ?string
    {
        return $this->parent_request_id_friendlyname;
    }
    public function setParentRequestIdFriendlyname(?string $parent_request_id_friendlyname): self
    {
        $this->parent_request_id_friendlyname = $parent_request_id_friendlyname;
        return $this;
    }

    public function getParentRequestIdArchiveFlag(): ?string
    {
        return $this->parent_request_id_archive_flag;
    }
    public function setParentRequestIdArchiveFlag(?string $parent_request_id_archive_flag): self
    {
        $this->parent_request_id_archive_flag = $parent_request_id_archive_flag;
        return $this;
    }

    public function getParentIncidentIdFriendlyname(): ?string
    {
        return $this->parent_incident_id_friendlyname;
    }
    public function setParentIncidentIdFriendlyname(?string $parent_incident_id_friendlyname): self
    {
        $this->parent_incident_id_friendlyname = $parent_incident_id_friendlyname;
        return $this;
    }

    public function getParentIncidentIdArchiveFlag(): ?string
    {
        return $this->parent_incident_id_archive_flag;
    }
    public function setParentIncidentIdArchiveFlag(?string $parent_incident_id_archive_flag): self
    {
        $this->parent_incident_id_archive_flag = $parent_incident_id_archive_flag;
        return $this;
    }

    public function getParentChangeIdFriendlyname(): ?string
    {
        return $this->parent_change_id_friendlyname;
    }
    public function setParentChangeIdFriendlyname(?string $parent_change_id_friendlyname): self
    {
        $this->parent_change_id_friendlyname = $parent_change_id_friendlyname;
        return $this;
    }

    public function getParentChangeIdFinalclassRecall(): ?string
    {
        return $this->parent_change_id_finalclass_recall;
    }
    public function setParentChangeIdFinalclassRecall(?string $parent_change_id_finalclass_recall): self
    {
        $this->parent_change_id_finalclass_recall = $parent_change_id_finalclass_recall;
        return $this;
    }

    public function getParentChangeIdArchiveFlag(): ?string
    {
        return $this->parent_change_id_archive_flag;
    }
    public function setParentChangeIdArchiveFlag(?string $parent_change_id_archive_flag): self
    {
        $this->parent_change_id_archive_flag = $parent_change_id_archive_flag;
        return $this;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date;
    }
    public function setDueDate(?string $due_date): self
    {
        $this->due_date = $due_date;
        return $this;
    }
}