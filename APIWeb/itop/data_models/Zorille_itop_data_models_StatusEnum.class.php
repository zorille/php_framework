<?php

namespace Zorille\itop\data_models;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PRODUCTION = 'production';
}