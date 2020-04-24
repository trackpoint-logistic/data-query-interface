<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

use Trackpoint\DataQueryInterface\DataQueryInterface;

class FeatureRelation
{

	private InterfaceFeature $feature;
	private string $join;
	private bool $visited;

	public function __construct(InterfaceFeature $feature, bool &$visited, string $join, $type)
	{
		$this->feature = $feature;
		$this->join    = $join;
		$this->type    = $type;
		$this->visited = &$visited;
	}

	public function setFeature(InterfaceFeature $feature): void
	{
		$this->feature = $feature;
	}

	public function isVisited(): bool
	{
		return $this->visited;
	}

	public function reset(): void
	{
		$this->visited = false;
	}

	public function visited(): void
	{
		$this->visited = true;
	}

	public function getJoin(): string
	{
		return $this->join;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getRelatedFeature(): InterfaceFeature
	{
		return $this->feature;
	}
}
