<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Metadata;



class AttributeMetadata
{
	const CONSTRAINT = 'constrain';
	const NO_CONSTRAINT = 0;
	const PRIMARY_CONSTRAINT = 1;
	const FOREIGN_CONSTRAINT = 2;

	private string $name;
	private int $constrain = self::NO_CONSTRAINT;

	public function __construct(string $name, array $properties = [])
	{
		$this->name = $name;

		foreach ($properties as $name => $value) {
			if (property_exists($this, $name) == false) {
				continue;
			}

			$this->$name = $value;
		}
	}



	public function getName(): string
	{
		return $this->name;
	}

	public function getConstrain(): int
	{
		return $this->constrain;
	}
}
