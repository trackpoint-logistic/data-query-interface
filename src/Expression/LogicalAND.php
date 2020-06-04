<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;


class LogicalAND implements FitInterface
{
	private ExpressionInterface $right;
	private ExpressionInterface $left;

	public function __construct(ExpressionInterface $left, ExpressionInterface $right)
	{
		$this->left = $left;
		$this->right = $right;
	}

	public function getRight(): ExpressionInterface
	{
		return $this->right;
	}

	public function getLeft(): ExpressionInterface
	{
		return $this->left;
	}

	public function fit(array $tuple): bool
	{
		return $this->left->fit($tuple) && $this->right->fit($tuple);
	}
}
