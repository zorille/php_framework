<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\OSMonitoring;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class OSMonitoringFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return 'OSMonitoring';
    }

    protected function getAssociatedModel(): string
    {
        return OSMonitoring::class;
    }
}