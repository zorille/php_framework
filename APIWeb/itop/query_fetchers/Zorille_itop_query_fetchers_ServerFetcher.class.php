<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Server;
use Zorille\itop\query_builder;

class ServerFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Server', 'S'];
    }

    protected function getAssociatedModel(): string
    {
        return Server::class;
    }
}