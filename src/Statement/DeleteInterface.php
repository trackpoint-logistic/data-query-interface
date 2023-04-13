<?php

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;
use Trackpoint\DataQueryInterface\Expression\Condition;

interface DeleteInterface extends StatementInterface
{
	public function delete(
		Condition $condition,
		array $returning
	): Generator;
}