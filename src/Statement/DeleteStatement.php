<?php

namespace Trackpoint\DataQueryInterface\Statement;

use Generator;
use Trackpoint\DataQueryInterface\Expression\Condition;
use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

class DeleteStatement implements StatementInterface
{
	private string $name;

	private Condition $condition;
	private Set $returning;

	protected DeleteInterface $interface;

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

	public function delete(
		Condition $condition
	): Generator
	{
		yield from $this->interface->delete(
			$condition,
			$this->returning->toArray());
	}
}