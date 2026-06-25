<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CMDBChangeOpCreate;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class CMDBChangeOpCreateFetcher extends query_builder
{
	protected static function getObjectName(): string|array
	{
		return 'CMDBChangeOpCreate';
	}

	protected function getAssociatedModel(): string
	{
		return CMDBChangeOpCreate::class;
	}
}
