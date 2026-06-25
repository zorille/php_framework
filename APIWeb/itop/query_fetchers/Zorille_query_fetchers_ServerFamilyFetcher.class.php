<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\ServiceFamily;
use Zorille\itop\query_builder;

class ServiceFamilyFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['ServiceFamily', 'SF'];
    }

    protected function getAssociatedModel(): string
    {
        return ServiceFamily::class;
    }
}