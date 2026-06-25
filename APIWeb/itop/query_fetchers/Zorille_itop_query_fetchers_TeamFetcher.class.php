<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Team;
use Zorille\itop\query_builder;

class TeamFetcher extends query_builder
{
    protected static function getObjectName(): string
    {
        return 'Team';
    }

    protected function getAssociatedModel(): string
    {
        return Team::class;
    }
}