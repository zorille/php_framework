<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CameraVideo;
use Zorille\itop\query_builder;

class CameraVideoFetcher extends query_builder
{
    protected static function getObjectName(): string|array
    {
        return ['CameraVideo', 'CV'];
    }

    protected function getAssociatedModel(): string
    {
        return CameraVideo::class;
    }
}