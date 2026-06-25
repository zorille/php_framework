<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\LnkCrmAssetsToFunctionalCI;
use Zorille\itop\query_builder;

class LnkCrmAssetsToFunctionalCIFetcher extends query_builder
{

    protected static function getObjectName(): string|array
    {
        return ['lnkCrmAssetsToFunctionalCI_1', 'LCATFC_1'];
    }

    protected function getAssociatedModel(): string
    {
        return LnkCrmAssetsToFunctionalCI::class;
    }
}