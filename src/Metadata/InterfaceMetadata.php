<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Metadata;

use Ds\Map;

class InterfaceMetadata
{

	private Map $attributes;

	public function __construct()
	{
		$this->attributes = new Map();
	}

	public function getAttribute($name): AttributeMetadata
	{
		return $this->attributes->get($name);
	}

	public function getAttributes(): Map
	{
		return $this->attributes;
	}
}
