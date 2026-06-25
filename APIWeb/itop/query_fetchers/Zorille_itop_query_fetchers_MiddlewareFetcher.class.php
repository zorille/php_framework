<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\Middleware;

class MiddlewareFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Middleware', 'MW'];
    }

    protected function getAssociatedModel(): string
    {
        return Middleware::class;
    }
}