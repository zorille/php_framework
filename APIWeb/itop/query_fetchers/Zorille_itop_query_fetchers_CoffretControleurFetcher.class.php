<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CoffretControleur;
use Zorille\itop\query_builder;

class CoffretControleurFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['CoffretControleur', 'CC'];
    }

    protected function getAssociatedModel(): string
    {
        return CoffretControleur::class;
    }
}