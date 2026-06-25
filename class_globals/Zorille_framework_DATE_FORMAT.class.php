<?php

namespace Zorille\framework;

class DATE_FORMAT
{
	public function __construct(
		private readonly DATE_SUB $sub,
		private readonly string $format
	) {}

	public function __toString(): string
	{
		return "DATE_FORMAT({$this->sub}, '{$this->format}')";
	}
}