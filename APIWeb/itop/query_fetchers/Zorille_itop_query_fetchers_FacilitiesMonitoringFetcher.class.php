<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\FacilitiesMonitoring;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class FacilitiesMonitoringFetcher extends query_builder
{

    protected static function getObjectName(): string|array
    {
        return ['FacilitiesMonitoring', 'FM'];
    }

    protected function getAssociatedModel(): string
    {
        return FacilitiesMonitoring::class;
    }
}