<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\Expression\Condition;

use Generator;

interface SelectInterface extends StatementInterface
{
	public function fetch(Condition $condition): Generator;
}
