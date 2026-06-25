<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\FiberChannelInterface;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class FiberChannelInterfaceFetcher extends query_builder
{

    protected static function getObjectName(): string|array
    {
        return ['FiberChannelInterface', 'FCI'];
    }

    protected function getAssociatedModel(): string
    {
        return FiberChannelInterface::class;
    }
}