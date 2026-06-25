<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\FiberPanel;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class FiberPanelFetcher extends query_builder
{

    protected static function getObjectName(): string|array
    {
        return ['FiberPanel', 'FP'];
    }

    protected function getAssociatedModel(): string
    {
        return FiberPanel::class;
    }
}