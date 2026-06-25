<?php

namespace Zorille\itop;

use Zorille\framework as Core;

class data_model extends Core\data_model
{
    protected static function formatArrayKey($property): string
    {
        if (is_string($property)) {
            return $property === 'StockElement_list' ? $property : lcfirst($property);
        }
        return $property->getName() === 'StockElement_list' ? $property->getName() : lcfirst($property->getName());
    }
}