<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\Expression\Condition;

use Generator;


interface UpdateInterface extends SelectInterface
{
	public function update(Condition $condition, array $data): Generator;
	//public function fetch(Condition $condition): Generator;
}
