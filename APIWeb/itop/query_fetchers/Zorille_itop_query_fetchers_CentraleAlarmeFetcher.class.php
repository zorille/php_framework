<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CentraleAlarme;
use Zorille\itop\query_builder;

class CentraleAlarmeFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['CentraleAlarme', 'CA'];
    }

    protected function getAssociatedModel(): string
    {
        return CentraleAlarme::class;
    }
}
