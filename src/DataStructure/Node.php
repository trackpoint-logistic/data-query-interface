<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\DataStructure;

class Node
{
	public int $index;
	public array $tuple;

	public $left;
	public $right;

	public function __construct(array $tuple, int $index)
	{
		$this->tuple = $tuple;
		$this->index = $index;
	}
}
