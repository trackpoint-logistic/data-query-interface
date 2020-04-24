<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

use Trackpoint\DataQueryInterface\Expression\Condition;
use Trackpoint\DataQueryInterface\Metadata\InterfaceMetadata;

use Ds\Map;
use Ds\Set;

class InterfaceFeature
{

	private InterfaceMetadata $metadata;
	private string $name;
	private Condition $condition;
	private Set $returning;
	private Map $data;
	private Set $relations;

	public function __construct(string $name, InterfaceMetadata $metadata)
	{
		$this->name      = $name;
		$this->metadata  = $metadata;
		$this->condition = new Condition();
		$this->returning = new Set();
		$this->data      = new Map();
		$this->relations = new Set();
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

	public function getRelations(): Set
	{
		return $this->relations;
	}

	public function getMetadata(): InterfaceMetadata
	{
		return $this->metadata;
	}
}
