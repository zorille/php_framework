<?php

namespace Zorille\itop\data_models;

enum CriticityEnum: string
{
    case HIGH = 'high';
    case LOW = 'low';
    case MEDIUM = 'medium';
}
