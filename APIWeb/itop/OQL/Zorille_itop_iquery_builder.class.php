<?php

namespace Zorille\itop;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework as core;

interface iquery_builder extends core\query_builder
{
    #[ArrayShape([
        'objects' => 'array'
    ])]
    public function toModel(): array;
    /**
     * @template T of (string[]|string|null)
     * @param T $q
     * @return (T is null ? self : array)
     * @throws Exception
     */
    public function build(string|array|null $q = null): iquery_builder|array;
}