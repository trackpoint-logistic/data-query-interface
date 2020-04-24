<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;


use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\UpdateStatement;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

use Exception;
use Generator;

class UpdateExecutor implements ExecutorInterface
{


	private function proceed(StatementInterface $node): Generator
	{
		if ($node instanceof JoinInterface) {
			yield from $node->merge(
				$this->proceed($node->getRightInterface())
			);
		} else if ($node instanceof SelectStatement) {
			yield from $node->fetch(
				$node->getCondition()
			);
		} else if ($node instanceof UpdateStatement) {
			yield from $node->update(
				$node->getCondition(),
				$node->getData()->toArray()
			);
		} else {
			throw new Exception(var_export($node, true));
		}
	}


	public function execute(StatementInterface $tree): Generator
	{
		yield from $this->proceed($tree);
	}
}
