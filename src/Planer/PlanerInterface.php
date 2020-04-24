<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Planer;

use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;

interface PlanerInterface
{

	public function getExecutionPlan(InterfaceFeature $feature): StatementInterface;
}
