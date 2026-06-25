<?php

namespace Zorille\framework;

use Zorille\framework\QueryBuilderOperator as QLOperator;

interface query_builder
{
    public function __construct();

    public static function create(): self;

    public static function setConnexion($connexion): void;

//    public function get(string $uri = '', array $queryString = []): self;
//    public function post(string $uri, array $body = []): self;
//    public function put(string $uri, array $body = []): self;
//    public function patch(string $uri, array $body = []): self;
//    public function delete(string $uri, array $body = []): self;

    public function toModel(): array;

    public function getResult();

    public function setQuery($query): self;

    public function setWsClient(): self;
    public function getWsClient();

    public function select(string ...$fields): self;
    public function where(string $var, QLOperator $operator, $value): self;
    public function and(): self;
    public function or(): self;
}