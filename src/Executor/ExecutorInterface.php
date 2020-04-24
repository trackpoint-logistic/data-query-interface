<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;

use Trackpoint\DataQueryInterface\Statement\StatementInterface;


use Generator;

interface ExecutorInterface
{
	public function execute(StatementInterface $tree): Generator;
}
