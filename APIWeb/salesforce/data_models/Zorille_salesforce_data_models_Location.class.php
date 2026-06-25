<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

class Location extends data_model
{
    const ENTITY_NAME = 'Location';

    protected array $virtualProperties = [
        'Attributes' => [
            'class' => Attributes::class,
            'value' => null
        ],
    ];

    protected ?string $id = null;
    protected ?string $name = null;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): Location
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): Location
    {
        $this->name = $name;
        return $this;
    }
}