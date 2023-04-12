<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;

use Generator;

/**
 * @deprecated
 */
class LeftJoin implements JoinInterface
{
	private SelectStatement $left;
	private StatementInterface $right;
	private string $relation;

	public function __construct(
        SelectStatement $left,
        StatementInterface $right,
        string $relation
    ){
		$this->left     = $left;
		$this->right    = $right;
		$this->relation = $relation;
	}

	public function getLeftNode(): StatementInterface
	{
		return $this->left;
	}

	public function getRightNode(): StatementInterface
	{
		return $this->right;
	}

    /**
     * Правый датасет это то что я поднимаю снизу
     * Левый это новый датасет что я добовляю
     * @param Generator $right
     * @return Generator
     */
	public function merge(
		Generator $right
	): Generator
	{

		$left = $this->left->fetch($this->left->getCondition());

		$bst = new BinarySearchTree($this->relation);
		$bst->fill($left);

		foreach($right as $right_tuple){

            $left_tuple = $bst->get($right_tuple[$this->relation]);

			/**
			 * Важно заметить что новое значение не перезаписывает старое
			 */
            yield $left_tuple
                ? array_merge($left_tuple, $right_tuple)
                : $right_tuple;
		}

        unset($bst);
	}
}
