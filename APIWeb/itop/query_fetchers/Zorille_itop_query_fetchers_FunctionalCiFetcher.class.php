<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\FunctionalCI;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class FunctionalCiFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return FunctionalCI::class;
    }

    protected static function getObjectName(): string|array
    {
        return ['FunctionalCI', 'CI'];
    }
}
