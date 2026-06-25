<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\Product2;
use Zorille\salesforce\query_builder;

class OpportunityProductFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return Product2::class;
    }

    protected function getObjectName(): string
    {
        return 'Product2';
    }
}