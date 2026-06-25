<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\StorageSystem;
use Zorille\itop\query_builder;

class StorageSystemFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['StorageSystem', 'SS'];
    }

    protected function getAssociatedModel(): string
    {
        return StorageSystem::class;
    }
}