<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Wifi;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class WifiFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Wifi', 'W'];
    }

    protected function getAssociatedModel(): string
    {
        return Wifi::class;
    }
}