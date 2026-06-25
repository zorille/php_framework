<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\WebServer;

class WebServerFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['WebServer', 'WS'];
    }

    protected function getAssociatedModel(): string
    {
        return WebServer::class;
    }
}