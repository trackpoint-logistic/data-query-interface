<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\DataStructure;

class Node
{
	public int $index;

	public $left;
	public $right;

	public function __construct(int $index)
	{
		$this->index = $index;
	}
}
