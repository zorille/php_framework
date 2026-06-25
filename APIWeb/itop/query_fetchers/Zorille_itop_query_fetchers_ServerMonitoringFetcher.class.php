<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\ServerMonitoring;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class ServerMonitoringFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['ServerMonitoring', 'SM'];
    }

    protected function getAssociatedModel(): string
    {
        return ServerMonitoring::class;
    }
}