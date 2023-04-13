<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;

use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\InsertStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Generator;

class InsertExecutor implements ExecutorInterface
{
	private function proceed(
		StatementInterface $node
	): Generator
	{
		if ($node instanceof InsertStatement) {
			yield from $node->insert(
				$node->getData()->toArray());

		} else if ($node instanceof JoinInterface) {

			$right = $this->proceed(
				$node->getRightNode());

			$data = $node->getLeftNode()
				->getData()
				->toArray();

			foreach ($right as $right_tuple) {

				$data[$node->getRelationKey()] = $right_tuple[$node->getRelationKey()];

				$left = $node->getLeftNode()
					->insert($data);

				foreach ($left as $left_tuple) {
					yield array_merge(
						$left_tuple,
						$right_tuple);
				}
			}
		}
	}

	public function execute(StatementInterface $tree): Generator
	{
		yield from $this->proceed($tree);
	}
}
