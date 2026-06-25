<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\VirtualMachine;

class VirtualMachineFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['VirtualMachine', 'VM'];
    }

    protected function getAssociatedModel(): string
    {
        return VirtualMachine::class;
    }
}