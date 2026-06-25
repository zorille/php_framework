<?php

namespace Zorille\salesforce\data_models;

use Exception;
use Zorille\framework\abstract_log;
use Zorille\salesforce\data_model;
use Zorille\salesforce\query_builder;
use Zorille\salesforce\query_fetchers\ContactFetcher;

/**
 * @method static self create()
 */
class ProductFamily extends data_model
{
    const ENTITY_NAME = 'ProductFamily';

    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}