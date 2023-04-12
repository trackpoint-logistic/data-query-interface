<?php

namespace Trackpoint\DataQueryInterface\Statement;

class InnerJoin implements JoinInterface
{
	private SelectStatement $left;
	private StatementInterface $right;
	private string $relation;

	public function __construct(
		SelectStatement $left,
		StatementInterface $right, $relation
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
