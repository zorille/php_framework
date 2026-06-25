<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\AppsMonitoring;
use Zorille\itop\query_builder;

class AppsMonitoringFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['AppsMonitoring', 'FM'];
    }

    protected function getAssociatedModel(): string
    {
        return AppsMonitoring::class;
    }
}