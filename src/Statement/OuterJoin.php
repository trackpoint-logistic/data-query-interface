<?php

declare(strict_types=1);


namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;

use Generator;

class LeftJoin implements JoinInterface
{

	private SelectStatement $left;
	private StatementInterface $right;
	private $relation;

	public function __construct(SelectStatement $left, StatementInterface $right, $relation)
	{
		$this->left     = $left;
		$this->right    = $right;
		$this->relation = $relation;
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

		$left = $this->left->fetch($this->left->getCondition());

		$left_bst = new BinarySearchTree($this->relation);
		$left_bst->fill($left);

		$right_bst = new BinarySearchTree($this->relation);

		foreach ($right as $right_tuple) {
			if (($left_tuple = $left_bst->search($right_tuple[$this->relation])) !== null) {
				$right_bst->insert(array_merge($left_tuple, $right_tuple));
				yield array_merge($left_tuple, $right_tuple);
			} else {
				$right_bst->insert($right_tuple);
				yield $right_tuple;
			}
		}

		$left_bst = null;
		foreach ($left as $left_tuple) {
			if ($right_bst->search($left_tuple[$this->relation]) == null) {
				yield $left_tuple;
			}
		}
	}
}
