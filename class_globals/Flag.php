<?php

namespace Zorille\framework;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Flag
{
    public function __construct(
        private ?string $flag = null,
        private ?string $type = null,
        private ?string $envAlias = null,
    )
    {}

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getEnvAlias(): ?string
    {
        return $this->envAlias;
    }
}