<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Location;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class LocationFetcher extends query_builder
{
    protected static function getObjectName(): array
    {
        return ['Location', 'L'];
    }

    protected function getAssociatedModel(): string
    {
        return Location::class;
    }
}