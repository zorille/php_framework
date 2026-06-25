<?php

namespace Zorille\framework;

class INTERVAL
{
	public function __construct(private readonly int $value, private readonly string $unit) {}

	public function __toString(): string
	{
		return "INTERVAL {$this->value} {$this->unit}";
	}
}
