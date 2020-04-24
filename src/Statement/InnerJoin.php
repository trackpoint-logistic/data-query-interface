<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;

use Generator;

class InnerJoin implements JoinInterface
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

		$bst = new BinarySearchTree($this->relation);
		$bst->fill($right);

		$left = $this->left->fetch($this->left->getCondition());

		foreach ($left as $left_tuple) {
			if (($right_tuple = $bst->search($left_tuple[$this->relation])) !== null) {
				yield array_merge($left_tuple, $right_tuple);
			}
		}
	}
}
