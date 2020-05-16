<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Metadata;

use Ds\Map;

interface InterfaceMetadata
{
	public function getAttribute($name): AttributeMetadata;
	public function getAttributes(): Map;
}
