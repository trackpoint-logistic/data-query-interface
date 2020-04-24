<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;

use Ds\Map;

class Condition
{
	private FitInterface $expression;
	private int $depth = 0;
	private Map $immutableExpressions;

	public function __construct()
	{

		$this->immutableExpressions = new Map();
	}

	public function add(ExpressionInterface $expression, bool $immutable = false): void
	{

		if ($immutable) {
			$this->immutableExpressions->put($expression->getName(), $expression);
		}

		if (isset($this->expression) == false) {
			$this->expression = $expression;
		} else {
			$this->expression = new LogicalAND($expression, $this->expression);
		}

		$this->depth++;
	}

	public function depth(): int
	{
		return $this->depth;
	}

	public function setImmutableExpressionConstantValue(array $tuple): Condition
	{

		foreach ($this->immutableExpressions as $expresion) {
			$expresion->setConstant(
				$tuple[$expresion->getName()] ?? null
			);
		}

		return $this;
	}

	/* 	public function getExpressionNameConstant()
	{
		$expression = $this->expression;

		while ($expression) {

			if ($expression instanceof LogicalAND) {
				$exp = $expression->getLeft();
				$expression = $expression->getRight();
			} else {
				$exp = $expression;
				$expression = null;
			}

			yield $exp->getName() => $exp->getConstant();
		}
	} */

	public function fit(array $tuple): bool
	{
		return $this->expression->fit($tuple);
	}
}