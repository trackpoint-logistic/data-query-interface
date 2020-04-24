<?php declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Resolver;

use Trackpoint\DataQueryInterface\Metadata\InterfaceMetadata;

interface MetadataProviderInterface{

	public function getInterfaceMetadata(string $name): InterfaceMetadata;
}