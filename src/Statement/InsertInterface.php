<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;

interface InsertInterface extends StatementInterface
{
	public function insert(
		array $returning,
		array $data
	): Generator;
}
