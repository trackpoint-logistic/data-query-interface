<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\InterfaceFeature;

use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;
use Trackpoint\DataQueryInterface\Expression\Condition;

use Generator;
use Ds\Set;

class SelectStatement implements StatementInterface
{
	private string $name;

	private Condition $condition;
	private Set $returning;

	protected SelectInterface $interface;

	public function __construct(
		InterfaceResolver $resolver,
		InterfaceFeature $feature
	){
		$this->resolver = $resolver;

		$this->name      = $feature->getName();
		$this->condition = $feature->getCondition();
		$this->returning = $feature->getReturning();

		$this->interface = $resolver->newInterfaceInstance($this);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getCondition(): Condition
	{
		return $this->condition;
	}

	public function getReturning(): Set
	{
		return $this->returning;
	}

	public function fetch(
		Condition $condition
	): Generator
	{
		yield from $this->interface->fetch(
			$condition,
			$this->returning->toArray());
	}
}
