<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\FiberRocade;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class FiberRocadeFetcher extends query_builder
{

    protected static function getObjectName(): string|array
    {
        return ['FiberRocade', 'FR'];
    }

    protected function getAssociatedModel(): string
    {
        return FiberRocade::class;
    }
}