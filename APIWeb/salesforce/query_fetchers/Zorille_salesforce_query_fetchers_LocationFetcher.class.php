<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\Location;
use Zorille\salesforce\query_builder;

/**
 * @method static self create()
 */
class LocationFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return Location::class;
    }

    protected function getObjectName(): string
    {
        return 'Location__c';
    }
}