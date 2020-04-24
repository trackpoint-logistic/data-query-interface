<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;

use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\InsertStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

use Generator;

class InsertExecutor implements ExecutorInterface
{

	private function proceed(StatementInterface $node): Generator
	{
		if ($node instanceof InsertStatement) {
			yield from $node->insert($node->getData()->toArray());
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
