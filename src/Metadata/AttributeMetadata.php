<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Metadata;


use Attribute;
use Stringable;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class AttributeMetadata implements Stringable
{


	const NO_CONSTRAINT = 0;
	const PRIMARY = 1;
	const FOREIGN = 2;

	const READ  = 0b0001;
	const WRITE = 0b0010;

	private string $name;
	private int $constrain;
	private int $mode;

	public function __construct(
		string $name,
		int $constrain = self::NO_CONSTRAINT,
		int $mode = (self::READ | self::WRITE)
	){
		$this->name = $name;
		$this->constrain = $constrain;
		$this->mode = $mode;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getConstrain(): int
	{
		return $this->constrain;
	}

	public function isPrimaryKey():bool{
		return $this->constrain == self::PRIMARY;
	}

	public function isForeignKey():bool{
		return $this->constrain == self::FOREIGN;
	}

	public function isReadable():bool{
		return (bool) ($this->mode & self::READ);
	}

	public function isWritable():bool{
		return (bool) ($this->mode & self::WRITE);
	}

	public function __toString(): string{
		return $this->name;
	}
}
