<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\EnregistreurCCTV;
use Zorille\itop\query_builder;

class EnregistreurCCTVFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['EnregistreurCCTV', 'EnregCCTV'];
    }

    protected function getAssociatedModel(): string
    {
        return EnregistreurCCTV::class;
    }
}