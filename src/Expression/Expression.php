<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;

abstract class Expression implements ExpressionInterface
{
	protected string $name;
	protected $constant;

	protected bool $disjunction = false;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function setDisjunction(bool $disjunction): void
	{
		$this->disjunction = $disjunction;
	}

	public function isDisjunction(): bool
	{
		return $this->disjunction;
	}

	public function setConstant($constant): void
	{
		$this->constant = $constant;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getConstant()
	{
		return $this->constant;
	}

	abstract public function fit(array $tuple): bool;
}
