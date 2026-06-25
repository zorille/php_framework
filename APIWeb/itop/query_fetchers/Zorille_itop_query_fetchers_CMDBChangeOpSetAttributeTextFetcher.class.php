<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CMDBChangeOpSetAttributeText;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class CMDBChangeOpSetAttributeTextFetcher extends query_builder
{
	protected static function getObjectName(): string|array
{
	return 'CMDBChangeOpSetAttributeText';
}

	protected function getAssociatedModel(): string
	{
		return CMDBChangeOpSetAttributeText::class;
	}

}
