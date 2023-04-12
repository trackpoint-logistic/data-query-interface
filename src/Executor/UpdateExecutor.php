<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Executor;


use Trackpoint\DataQueryInterface\Expression\Expression;
use Trackpoint\DataQueryInterface\Statement\JoinInterface;
use Trackpoint\DataQueryInterface\Statement\UpdateStatement;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

use Exception;
use Generator;

class UpdateExecutor implements ExecutorInterface
{

	private function proceed(
		StatementInterface $node
	): Generator
	{
		if ($node instanceof JoinInterface) {

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
		} else if ($node instanceof SelectStatement) {
			yield from $node->fetch(
				$node->getCondition()
			);
		} else if ($node instanceof UpdateStatement) {
			yield from $node->update(
				$node->getCondition(),
				$node->getData()->toArray());
		} else {
			throw new Exception(var_export($node, true));
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
