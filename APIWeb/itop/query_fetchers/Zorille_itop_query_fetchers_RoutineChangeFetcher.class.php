<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\RoutineChange;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class RoutineChangeFetcher extends query_builder
{
    protected static function getObjectName(): array
    {
        return ['RoutineChange', 'RC'];
    }

    protected function getAssociatedModel(): string
    {
        return RoutineChange::class;
    }
}