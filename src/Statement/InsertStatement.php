<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

use Trackpoint\DataQueryInterface\InterfaceFeature;

use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

use Generator;
use Ds\Set;
use Ds\Map;

class InsertStatement implements StatementInterface
{
	private string $name;

	private Set $returning;
	private Map $data;

	protected InsertInterface $interface;

	public function __construct(InterfaceResolver $resolver, InterfaceFeature $feature)
	{
		$this->resolver = $resolver;

		$this->name      = $feature->getName();

		$this->returning = $feature->getReturning();
		$this->data      = $feature->getData();

		$this->interface = $resolver->newInterfaceInstance($this);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getReturning(): Set
	{
		return $this->returning;
	}

	public function getData(): Map
	{
		return $this->data;
	}

	public function insert(array $data): Generator
	{
		yield from $this->interface->insert(
			$this->returning->toArray(),
			$data);
	}
}
