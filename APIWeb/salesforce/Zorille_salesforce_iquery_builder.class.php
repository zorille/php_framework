<?php

namespace Zorille\salesforce;

use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework as core;

interface iquery_builder extends core\query_builder
{
    #[ArrayShape([
        'totalSize' => 'integer',
        'done' => 'boolean',
        'records' => 'array'
    ])]
    public function toModel(): array;
    public function build(): self;
}