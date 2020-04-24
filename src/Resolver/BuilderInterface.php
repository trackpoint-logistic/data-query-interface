<?php declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Resolver;

use Trackpoint\DataQueryInterface\Statement\StatementInterface;

interface BuilderInterface{

	public function newInterfaceInstance(StatementInterface $statement);
}