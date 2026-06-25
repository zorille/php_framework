<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Service;
use Zorille\itop\query_builder;

class ServiceFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Service', 'S'];
    }

    protected function getAssociatedModel(): string
    {
        return Service::class;
    }
}