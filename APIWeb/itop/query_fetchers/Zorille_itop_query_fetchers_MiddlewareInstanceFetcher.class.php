<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\MiddlewareInstance;
use Zorille\itop\query_builder;

class MiddlewareInstanceFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['MiddlewareInstance', 'MI'];
    }

    protected function getAssociatedModel(): string
    {
        return MiddlewareInstance::class;
    }
}