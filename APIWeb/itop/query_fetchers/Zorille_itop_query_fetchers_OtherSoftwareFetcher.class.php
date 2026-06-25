<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\OtherSoftware;

class OtherSoftwareFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['OtherSoftware', 'OS'];
    }

    protected function getAssociatedModel(): string
    {
        return OtherSoftware::class;
    }
}