<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\ApplicationSolution;
use Zorille\itop\query_builder;

class ApplicationSolutionFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['ApplicationSolution', 'AppSol'];
    }

    protected function getAssociatedModel(): string
    {
        return ApplicationSolution::class;
    }
}