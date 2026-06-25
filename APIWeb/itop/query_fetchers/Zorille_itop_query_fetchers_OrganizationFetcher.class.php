<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Organization;
use Zorille\itop\query_builder;

class OrganizationFetcher extends query_builder
{
    protected static function getObjectName(): string
    {
        return 'Organization';
    }

    protected function getAssociatedModel(): string
    {
        return Organization::class;
    }
}