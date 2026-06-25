<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\NetworkDevice;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class NetworkDeviceFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['NetworkDevice', 'ND'];
    }

    protected function getAssociatedModel(): string
    {
        return NetworkDevice::class;
    }
}