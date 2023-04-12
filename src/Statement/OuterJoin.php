<?php

declare(strict_types=1);


namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\DataStructure\BinarySearchTree;

use Generator;
use Trackpoint\DataQueryInterface\Expression\EqualExpression;

/**
 * Внешние соединения.
 * Данное класс реализует правое внешнее соединение.
 * Добавляет к внутреннему соединению строки из правого набора,
 * для которых не нашлось соответствия в правом наборе (столбцы отсутствующего правого набора получают неопределенные значения).
 */
class OuterJoin implements JoinInterface
{

	private StatementInterface $left;
	private StatementInterface $right;
	private string $relation;

	public function __construct(
		StatementInterface $left,
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
	 * @return string
	 */
	public function getRelationKey(): string
	{
		return $this->relation;
	}
}
