<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;

class InsertJoin implements JoinInterface
{
	private InsertStatement $left;
	private StatementInterface $right;

	public function __construct(InsertStatement $left, StatementInterface $right)
	{
		$this->left     = $left;
		$this->right    = $right;
	}

	public function getLeftInterface(): StatementInterface
	{
		return $this->left;
	}

	public function getRightInterface(): StatementInterface
	{
		return $this->right;
	}

	public function merge(Generator $right): Generator
	{
		$data = $this->left->getData()->toArray();

		foreach ($right as $right_tuple) {

			foreach ($data as $name => $value) {
				$data[$name] = $value ?? $right_tuple[$name];
			}

			$left = $this->left->insert($data);

			foreach ($left as $left_tuple) {
				yield array_merge($right_tuple, $left_tuple);
			}
		}
	}
}
