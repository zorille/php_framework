<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Person;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class PersonFetcher extends query_builder
{
    protected static function getObjectName(): string
    {
        return 'Person';
    }

    public function getAssociatedModel(): string
    {
        return Person::class;
    }
}