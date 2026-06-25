<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Onduleur;
use Zorille\itop\query_builder;

class OnduleurFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Onduleur', 'O'];
    }

    protected function getAssociatedModel(): string
    {
        return Onduleur::class;
    }
}