<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\NAS;
use Zorille\itop\query_builder;

class NASFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['NAS', 'N'];
    }

    protected function getAssociatedModel(): string
    {
        return NAS::class;
    }
}