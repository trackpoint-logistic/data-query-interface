<?php

namespace Trackpoint\DataQueryInterface\Executor;

use Exception;
use Generator;
use Trackpoint\DataQueryInterface\Expression\Expression;
use Trackpoint\DataQueryInterface\Statement\DeleteStatement;
use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

class DeleteExecutor implements ExecutorInterface
{

	private function proceed(
		StatementInterface $node
	): Generator
	{
		if ($node instanceof DeleteStatement) {
			yield from $node->delete(
				$node->getCondition());

		} else if ($node instanceof JoinInterface) {

			$right = $this->proceed(
				$node->getRightNode());

			foreach ($right as $right_tuple) {

				/**
				 * По идее планировщик должен создавать дополнительные експрешены что бы передавать их в стейтмент
				 */
				$expression = $node->getLeftNode()
					->getCondition()
					->getExpressionByName(
						$node->getRelationKey());

				$constant = $right_tuple[$node->getRelationKey()] ?? $expression->getConstant();
				/**
				 * @var Expression $expression
				 */
				$expression->setConstant($constant);

				$left = $this->proceed(
					$node->getLeftNode());

				if($left->valid() == false){
					yield $right_tuple;
					continue;
				}

				foreach ($left as $left_tuple) {
					/**
					 * Важно заметить что новое значение не перезаписывает старое
					 */
					yield array_merge(
						$left_tuple,
						$right_tuple);
				}
			}
		}
	}

	/**
	 * @throws Exception
	 */
	public function execute(
		StatementInterface $tree
	): Generator
	{
		yield from $this->proceed($tree);
	}
}