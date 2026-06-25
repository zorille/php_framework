<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Incident;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class IncidentFetcher extends query_builder
{
    protected static function getObjectName(): string
    {
        return 'Incident';
    }

    protected function getAssociatedModel(): string
    {
        return Incident::class;
    }
}