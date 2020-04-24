<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

class DQL
{

	const STATEMENT = 'STATEMENT';

	//Vidi Steitmentov
	const SELECT   = 'SELECT';
	const INSERT   = 'INSERT';
	const UPDATE   = 'UPDATE';
	const DELETE   = 'DELETE';
	const EXECUTE  = 'EXECUTE';

	const ATTRIBUTES = 'ATTRIBUTES';

	const EXPRESSIONS = 'EXPRESSIONS';


	const SOURCE   = 'SOURCE';
	const MODEL   = 'MODEL';
	//U sourca est tipi
	const TYPE     = 'TYPE';
	//Etot tip mozet bit
	//const ENTITY      = 'ENTITY'; //Zamenili na source
	const INNER_MERGE = 'INNER_MERGE';
	const LEFT_MERGE  = 'LEFT_MERGE';

	//Esli eto ne entity
	const RELATION = 'RELATION';
	const LEFT     = 'LEFT';
	const RIGHT    = 'RIGHT';

	const UNION      = 'UNION';
	const JOIN       = 'JOIN';

	const INNER_JOIN = 'INNER_JOIN';
	const OUTER_JOIN = 'OUTER_JOIN';
	const LEFT_JOIN  = 'LEFT_JOIN';

	const ENTITY    = 'ENTITY';
	const INTERFACE = 'INTERFACE';

	//Esli eto entity
	const SCHEMA   = 'SCHEMA';
	const NAME     = 'NAME';
	const ALIAS   = 'ALIAS';

	//Tkaze u entity mogut bit
	const CONDITION = 'CONDITION';
	const RETURNING = 'RETURNING';
	const DATA      = 'DATA';

	//Condition
	//U virazenija est tip, on mozet bit 
	const DISJUNCTION   = 'DISJUNCTION';
	const EQUAL         = 'EQUAL';

	const GREATER_EQUAL = 'GREATER_EQUAL';
	const LESS_EQUAL    = 'LESS_EQUAL';

	const GREATER       = 'GREATER';
	const LESS          = 'LESS';

	const CONSTANT      = 'CONSTANT';
}
