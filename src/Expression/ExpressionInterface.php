<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;


interface ExpressionInterface extends FitInterface
{
	public function getName(): string;
}
