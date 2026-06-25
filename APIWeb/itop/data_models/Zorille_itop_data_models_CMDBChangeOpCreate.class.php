<?php

namespace Zorille\itop\data_models;

class CMDBChangeOpCreate extends CMDBChangeOp
{
    const ENTITY_NAME = 'CMDBChangeOpCreate';

	protected string $finalclass = 'CMDBChangeOpCreate';
	protected ?string $friendlyname = '';
}
