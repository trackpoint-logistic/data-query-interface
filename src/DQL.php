<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

class DQL
{
	const STATEMENT = 'STATEMENT';

	const SELECT   = 'SELECT';
	const INSERT   = 'INSERT';
	const UPDATE   = 'UPDATE';
	const DELETE   = 'DELETE';
	//const EXECUTE  = 'EXECUTE';

	const EXPRESSIONS = 'EXPRESSIONS';
	const CONDITION = 'CONDITION';
	const RETURNING = 'RETURNING';
	const DATA      = 'DATA';

	const RELATION = 'RELATION';
	const JOIN = 'JOIN';
	const TYPE     = 'TYPE';

	const INNER_JOIN = 'INNER_JOIN';
	const OUTER_JOIN = 'OUTER_JOIN';
	const LEFT_JOIN  = 'LEFT_JOIN';

	const INTERFACE = 'INTERFACE';

	const NAME     = 'NAME';

	const DISJUNCTION   = 'DISJUNCTION';
	const EQUAL         = 'EQUAL';

	const GREATER_EQUAL = 'GREATER_EQUAL';
	const LESS_EQUAL    = 'LESS_EQUAL';

	const GREATER       = 'GREATER';
	const LESS          = 'LESS';

	const CONSTANT      = 'CONSTANT';
}
