<?php

namespace Zorille\itop\data_models;

enum CMDBChangeOpSetAttributeLinksAddRemoveType: string
{
	case ADDED = 'added';
	case REMOVED = 'removed';
}
