<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\PDU;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class PDUFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return 'PDU';
    }

    protected function getAssociatedModel(): string
    {
        return PDU::class;
    }
}