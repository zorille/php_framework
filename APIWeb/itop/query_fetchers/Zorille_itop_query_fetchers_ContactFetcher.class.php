<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\query_builder;
use Zorille\itop\data_models\Contact;

class ContactFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['Contact', 'Contact'];
    }

    protected function getAssociatedModel(): string
    {
        return Contact::class;
    }
}