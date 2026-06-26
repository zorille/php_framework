<?php

namespace Zorille\itop;

enum QueryBuilderOperator: string
{
    case EQUALS = '=';
    case DIF = '!=';
    case SUP = '>';
    case SUP_EQ = '>=';
	case INF = '<';
	case INF_EQ = '<=';
	case LIKE = 'LIKE';
	case NOT_LIKE = 'NOT LIKE';
	case IN = 'IN';
	case NOT_IN = 'NOT IN';
    case REGEXP = 'REGEXP';
}
