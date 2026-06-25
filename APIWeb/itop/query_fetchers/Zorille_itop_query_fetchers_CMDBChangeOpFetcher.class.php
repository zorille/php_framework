<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\CMDBChangeOp;
use Zorille\itop\query_builder;

/**
 * @method static self create()
 */
class CMDBChangeOpFetcher extends query_builder
{
	protected static function getObjectName(): string|array
{
	return 'CMDBChangeOp';
}

	protected function getAssociatedModel(): string
	{
		return CMDBChangeOp::class;
	}

}
