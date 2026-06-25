<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\BoitierGTC;
use Zorille\itop\query_builder;

class BoitierGTCFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return 'BoitierGTC';
    }

    protected function getAssociatedModel(): string
    {
        return BoitierGTC::class;
    }
}