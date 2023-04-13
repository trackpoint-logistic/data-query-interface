<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;
use Trackpoint\DataQueryInterface\Statement\InnerJoin;
use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Generator;

class SelectExecutor implements ExecutorInterface
{

	private function proceed(
		StatementInterface $node
	): Generator
	{
		if ($node instanceof SelectStatement) {
			yield from $node->fetch(
				$node->getCondition());

		} else if ($node instanceof JoinInterface) {

			$right = $this->proceed(
				$node->getRightNode());

			$left = $this->proceed(
					$node->getLeftNode());

			$bst = new BinarySearchTree($node->getRelationKey());
			$bst->fill($left);

			yield from $node instanceof InnerJoin
				? $this->getInnerJoin(
					$right,
					$bst,
					$node->getRelationKey())

				: $this->getOuterJoin(
					$right,
					$bst,
					$node->getRelationKey());
		}
	}

	private function getOuterJoin(
		Generator $right,
		BinarySearchTree $left,
		string $key
	): Generator{
		foreach($right as $right_tuple){

			$left_tuple = $left->get($right_tuple[$key]) ?? [];

			/**
			 * Важно заметить что новое значение не перезаписывает старое
			 */
			yield array_merge($left_tuple, $right_tuple);
		}

	}

	private function getInnerJoin(
		Generator $right,
		BinarySearchTree $left,
		string $key
	): Generator{
		foreach($right as $right_tuple){
			/**
			 * Важно заметить что новое значение не перезаписывает старое
			 */
			$left_tuple = $left->get($right_tuple[$key]);
			if(empty($left_tuple)){
				continue;
			}

			yield array_merge($left_tuple, $right_tuple);
		}
	}

	public function execute(StatementInterface $tree): Generator
	{
		yield from $this->proceed($tree);
	}
}
