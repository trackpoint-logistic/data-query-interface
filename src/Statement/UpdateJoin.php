<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;

/**
 * @deprecated
 */
class UpdateJoin implements JoinInterface
{
	private UpdateStatement $left;
	private StatementInterface $right;

	public function __construct(UpdateStatement $left, StatementInterface $right)
	{
		$this->left     = $left;
		$this->right    = $right;
	}

	public function getLeftNode(): StatementInterface
	{
		return $this->left;
	}

	public function getRightNode(): StatementInterface
	{
		return $this->right;
	}

	private function dataExistsAndDifferent($new, $old)
	{
		foreach ($new as $name => $value) {
			if (isset($old[$name]) == false) {
				return false;
			}

			if ($old[$name] != $value) {
				return true;
			}
		}

		return false;
	}

	public function merge(Generator $right): Generator
	{
		$data = $this->left->getData()->toArray();
		$condition = $this->left->getCondition();

		foreach ($right as $right_tuple) {
			if ($this->dataExistsAndDifferent($data, $right_tuple)) {
				$condition->setImmutableExpressionConstantValue($right_tuple);

				$left = $this->left->update(
					$condition, //Uslovija po kotorim oni budut izmeneni
					$data //Dannie kotorie klient hochet zmenit
				);

				foreach ($left as $left_tuple) {
					yield array_merge($right_tuple, $left_tuple);
				}
			} else {
				yield $right_tuple;
			}
		}
	}
}
