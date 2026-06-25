<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\VMTemplate;
use Zorille\itop\query_builder;

class VMTemplateFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['VMTemplate', 'VMT'];
    }

    protected function getAssociatedModel(): string
    {
        return VMTemplate::class;
    }
}