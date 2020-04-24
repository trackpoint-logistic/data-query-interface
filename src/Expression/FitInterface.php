<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;


interface FitInterface
{
	public function fit(array $tuple): bool;
}
