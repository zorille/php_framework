<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\PCSoftware;

class PCSoftwareFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['PCSoftware', 'PCS'];
    }

    protected function getAssociatedModel(): string
    {
        return PCSoftware::class;
    }
}