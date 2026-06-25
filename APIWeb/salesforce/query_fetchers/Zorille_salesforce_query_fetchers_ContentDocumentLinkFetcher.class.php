<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\ContentDocumentLink;
use Zorille\salesforce\query_builder;

/**
 * @method static self create()
 */
class ContentDocumentLinkFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return ContentDocumentLink::class;
    }

    protected function getObjectName(): string
    {
        return 'ContentDocumentLink';
    }
}