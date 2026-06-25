<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

class Attributes extends data_model
{
    const ENTITY_NAME = 'Attributes';

    protected string $type;
    protected string $url;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}