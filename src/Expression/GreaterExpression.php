<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;

class GreaterExpression extends Expression
{
	public function fit(array $tuple): bool
	{
		if (isset($tuple[$this->name]) == false) {
			return false;
		}

		return $this->disjunction && ($tuple[$this->name] > $this->constant);
	}
}
