<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Rack;
use Zorille\itop\query_builder;

class RackFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Rack', 'R'];
    }

    protected function getAssociatedModel(): string
    {
        return Rack::class;
    }
}