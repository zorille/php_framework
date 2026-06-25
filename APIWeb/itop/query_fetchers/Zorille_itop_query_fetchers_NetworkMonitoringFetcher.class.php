<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\NetworkMonitoring;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class NetworkMonitoringFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['NetworkMonitoring', 'NM'];
    }

    protected function getAssociatedModel(): string
    {
        return NetworkMonitoring::class;
    }
}