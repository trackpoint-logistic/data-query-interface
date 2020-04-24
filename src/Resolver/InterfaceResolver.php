<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Resolver;

use Trackpoint\DataQueryInterface\Metadata\InterfaceMetadata;
use Trackpoint\DataQueryInterface\Resolver\BuilderInterface;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

class InterfaceResolver implements BuilderInterface, MetadataProviderInterface
{

	private MetadataProviderInterface $provider;
	private BuilderInterface $builder;

	public function __construct(BuilderInterface $builder, MetadataProviderInterface $provider)
	{
		$this->builder = $builder;
		$this->provider = $provider;
	}

	public function newInterfaceInstance(StatementInterface $statement)
	{
		return $this->builder->newInterfaceInstance($statement);
	}

	public function getInterfaceMetadata(string $name): InterfaceMetadata
	{
		return $this->provider->getInterfaceMetadata($name);
	}
}
