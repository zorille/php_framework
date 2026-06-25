<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\VMMonitoring;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class VMMonitoringFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return 'VMMonitoring';
    }

    protected function getAssociatedModel(): string
    {
        return VMMonitoring::class;
    }
}