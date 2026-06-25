<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\PhysicalDevice;
use Zorille\itop\query_builder;

class PhysicalDeviceFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['PhysicalDevice', 'PD'];
    }

    protected function getAssociatedModel(): string
    {
        return PhysicalDevice::class;
    }
}