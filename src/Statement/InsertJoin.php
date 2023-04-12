<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

/**
 * @deprecated
 */
class InsertJoin implements JoinInterface
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
