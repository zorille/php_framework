<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\NetworkInterface;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class NetworkInterfaceFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['NetworkInterface', 'NI'];
    }

    protected function getAssociatedModel(): string
    {
        return NetworkInterface::class;
    }
}