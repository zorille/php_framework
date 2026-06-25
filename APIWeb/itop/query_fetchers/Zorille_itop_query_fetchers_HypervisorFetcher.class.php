<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Hypervisor;
use Zorille\itop\query_builder;

class HypervisorFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Hypervisor', 'HV'];
    }

    protected function getAssociatedModel(): string
    {
        return Hypervisor::class;
    }
}