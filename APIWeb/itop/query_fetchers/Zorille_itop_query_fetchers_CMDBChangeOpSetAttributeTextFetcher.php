<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CMDBChangeOpSetAttributeText;
use Zorille\itop\query_builder;

class CMDBChangeOpSetAttributeTextFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['CMDBChangeOpSetAttributeText', 'CMDBChangeOpSetAttributeText'];
    }

    protected function getAssociatedModel(): string
    {
        return CMDBChangeOpSetAttributeText::class;
    }
}