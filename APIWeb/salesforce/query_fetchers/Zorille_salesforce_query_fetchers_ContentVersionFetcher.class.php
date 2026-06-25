<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\ContentDocumentLink;
use Zorille\salesforce\data_models\ContentVersion;
use Zorille\salesforce\query_builder;

/**
 * @method static self create()
 */
class ContentVersionFetcher extends query_builder
{
    protected function getAssociatedModel(): string
    {
        return ContentVersion::class;
    }

    protected function getObjectName(): string
    {
        return 'ContentVersion';
    }
}