<?php

namespace Zorille\framework;

class SingletonFactory
{
    private static array $singletons = [];

    protected final function getSingleton(string $name): mixed
    {
        return self::$singletons[$name] ?? null;
    }
    protected final function setIfNotExists(string $name, mixed $value): self
    {
        if (is_null($this->getSingleton($name))) {
            self::$singletons[$name] = $value;
        }

        return $this;
    }
}