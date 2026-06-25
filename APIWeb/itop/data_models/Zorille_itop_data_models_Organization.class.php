<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

/**
 * @method static self create()
 */
class Organization extends data_model
{
    const ENTITY_NAME = 'Organization';

    protected ?int $id = null;
    protected ?string $name = null;
    protected ?string $status = null;
    protected ?string $code = null;
    protected ?string $parent_id = null;
    protected ?string $parent_name = null;
    protected ?string $overview = null;
    protected ?string $deliverymodel_id = null;
    protected ?string $deliverymodel_name = null;
    protected ?string $euclyde_id = null;
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_date = null;
    protected ?string $parent_id_friendlyname = null;
    protected ?string $parent_id_obsolescence_flag = null;
    protected ?string $deliverymodel_id_friendlyname = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): Organization
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): Organization
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(?string $status): Organization
    {
        $this->status = $status;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
    public function setCode(?string $code): Organization
    {
        $this->code = $code;
        return $this;
    }

    public function getParentId(): ?string
    {
        return $this->parent_id;
    }
    public function setParentId(?string $parent_id): Organization
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    public function getParentName(): ?string
    {
        return $this->parent_name;
    }
    public function setParentName(?string $parent_name): Organization
    {
        $this->parent_name = $parent_name;
        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }
    public function setOverview(?string $overview): Organization
    {
        $this->overview = $overview;
        return $this;
    }

    public function getDeliverymodelId(): ?string
    {
        return $this->deliverymodel_id;
    }
    public function setDeliverymodelId(?string $deliverymodel_id): Organization
    {
        $this->deliverymodel_id = $deliverymodel_id;
        return $this;
    }

    public function getDeliverymodelName(): ?string
    {
        return $this->deliverymodel_name;
    }
    public function setDeliverymodelName(?string $deliverymodel_name): Organization
    {
        $this->deliverymodel_name = $deliverymodel_name;
        return $this;
    }

    public function getEuclydeId(): ?string
    {
        return $this->euclyde_id;
    }
    public function setEuclydeId(?string $euclyde_id): Organization
    {
        $this->euclyde_id = $euclyde_id;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    public function setFriendlyname(?string $friendlyname): Organization
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getObsolescenceDate(): ?string
    {
        return $this->obsolescence_date;
    }
    public function setObsolescenceDate(?string $obsolescence_date): Organization
    {
        $this->obsolescence_date = $obsolescence_date;
        return $this;
    }

    public function getParentIdFriendlyname(): ?string
    {
        return $this->parent_id_friendlyname;
    }
    public function setParentIdFriendlyname(?string $parent_id_friendlyname): Organization
    {
        $this->parent_id_friendlyname = $parent_id_friendlyname;
        return $this;
    }

    public function getParentIdObsolescenceFlag(): ?string
    {
        return $this->parent_id_obsolescence_flag;
    }
    public function setParentIdObsolescenceFlag(?string $parent_id_obsolescence_flag): Organization
    {
        $this->parent_id_obsolescence_flag = $parent_id_obsolescence_flag;
        return $this;
    }

    public function getDeliverymodelIdFriendlyname(): ?string
    {
        return $this->deliverymodel_id_friendlyname;
    }
    public function setDeliverymodelIdFriendlyname(?string $deliverymodel_id_friendlyname): Organization
    {
        $this->deliverymodel_id_friendlyname = $deliverymodel_id_friendlyname;
        return $this;
    }
}