<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\DataStructure;

class Node
{
	public int $index;
	public $data;

	public $left;
	public $right;

	public function __construct(int $index, $data)
	{
		$this->index = $index;
		$this->data = $data;
	}
}
