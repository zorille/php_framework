<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CustomerContract;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class CustomerContractFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return CustomerContract::class;
    }

    protected static function getObjectName(): string
    {
        return 'CustomerContract';
    }
}