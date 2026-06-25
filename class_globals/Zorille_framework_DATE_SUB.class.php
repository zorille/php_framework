<?php

namespace Zorille\framework;

class DATE_SUB
{
	public function __construct(private readonly mixed $date, private readonly mixed $calcul) {}

	public function __toString(): string
	{
		return "DATE_SUB({$this->date}, {$this->calcul})";
	}
}
