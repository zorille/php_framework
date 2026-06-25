<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\Account;
use Zorille\salesforce\query_builder;

class CustomerFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return Account::class;
    }
    protected function getObjectName(): string
    {
        return 'Account';
    }
}