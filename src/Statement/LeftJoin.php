<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;

use Generator;

/**
 * Реализуем правое глубокое дерево, данные поднимаем справа, и читаем их
 */
class LeftJoin implements JoinInterface
{
	private SelectStatement $left;
	private StatementInterface $right;
	private $relation;

	public function __construct(
        SelectStatement $left,
        StatementInterface $right,
        $relation
    ){
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

		$bst = new BinarySearchTree($this->relation);
		$bst->fill($left);

		foreach ($right as $right_tuple) {
			if (($left_tuple = $bst->search($right_tuple[$this->relation])) !== null) {
				yield array_merge($left_tuple, $right_tuple);
			} else {
				yield $right_tuple;
			}
		}
	}
}
