<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;

interface JoinInterface extends StatementInterface
{

	public function getRightInterface(): StatementInterface;
	public function getLeftInterface(): StatementInterface;

	public function merge(Generator $right): Generator;
}
