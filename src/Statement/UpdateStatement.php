<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\InterfaceFeature;

use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;
use Trackpoint\DataQueryInterface\Expression\Condition;

use Generator;
use Ds\Set;
use Ds\Map;

class UpdateStatement implements StatementInterface
{
	private string $name;

	private Condition $condition;
	private Set $returning;
	private Map $data;

	protected UpdateInterface $interface;

	public function __construct(InterfaceResolver $resolver, InterfaceFeature $feature)
	{
		$this->resolver = $resolver;

		$this->name      = $feature->getName();
		$this->condition = $feature->getCondition();
		$this->returning = $feature->getReturning();
		$this->data      = $feature->getData();

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

	public function getData(): Map
	{
		return $this->data;
	}

	//abstract public function update(Condition $condition, array $data): Generator;
	public function update(Condition $condition, array $data): Generator
	{
		yield from $this->interface->update($condition, $data);
	}
}
