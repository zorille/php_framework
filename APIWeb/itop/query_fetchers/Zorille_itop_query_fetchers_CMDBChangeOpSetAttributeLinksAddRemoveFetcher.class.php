<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CMDBChangeOpSetAttributeLinksAddRemove;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class CMDBChangeOpSetAttributeLinksAddRemoveFetcher extends query_builder
{
	protected static function getObjectName(): string|array
	{
		return 'CMDBChangeOpSetAttributeLinksAddRemove';
	}

	protected function getAssociatedModel(): string
	{
		return CMDBChangeOpSetAttributeLinksAddRemove::class;
	}
}
