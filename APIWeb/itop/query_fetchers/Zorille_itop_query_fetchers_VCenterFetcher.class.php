<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\VCenter;
use Zorille\itop\query_builder;

class VCenterFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['VCenter', 'VC'];
    }

    protected function getAssociatedModel(): string
    {
        return VCenter::class;
    }
}