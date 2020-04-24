<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;

use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

use Generator;

class SelectExecutor implements ExecutorInterface
{

	private function proceed(StatementInterface $node): Generator
	{
		if ($node instanceof SelectStatement) {
			yield from $node->fetch($node->getCondition());
		} else if ($node instanceof JoinInterface) {
			yield from $node->merge(
				$this->proceed($node->getRightInterface())
			);
		}
	}


	public function execute(StatementInterface $tree): Generator
	{
		yield from $this->proceed($tree);
	}
}
