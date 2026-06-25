<?php

namespace Zorille\itop;

enum QueryBuilderLikeOperatorType: string
{
    case START_BY = 'start_by';
    case END_BY = 'end_by';
    case CONTAINS = 'contains';
    case PATTERN = 'pattern';
}
