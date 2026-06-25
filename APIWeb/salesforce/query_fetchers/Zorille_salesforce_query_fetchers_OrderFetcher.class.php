<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\Order;
use Zorille\salesforce\query_builder;

class OrderFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return Order::class;
    }

    protected function getObjectName(): string
    {
        return 'Order';
    }
}