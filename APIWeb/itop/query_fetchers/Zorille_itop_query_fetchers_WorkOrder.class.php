<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\WorkOrder;

class WorkOrderFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['WorkOrder', 'WO'];
    }

    protected function getAssociatedModel(): string
    {
        return WorkOrder::class;
    }
}