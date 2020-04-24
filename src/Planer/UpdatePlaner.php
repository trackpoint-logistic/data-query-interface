<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Planer;


use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\DQL;


use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;

use Trackpoint\DataQueryInterface\Statement\InnerJoin;
use Trackpoint\DataQueryInterface\Statement\LeftJoin;

use Trackpoint\DataQueryInterface\Statement\UpdateJoin;
use Trackpoint\DataQueryInterface\Statement\UpdateStatement;
use Trackpoint\DataQueryInterface\Expression\Condition;
use Trackpoint\DataQueryInterface\Expression\EqualExpression;

use Generator;

class UpdatePlaner extends SelectPlaner
{

	public function __construct(InterfaceResolver $resolver)
	{
		parent::__construct($resolver);
	}

	public function getExecutionPlan(InterfaceFeature $feature): StatementInterface
	{
		$begin = $this->getEntryFeature($feature);

		$queue = $this->getExecutionQueue($begin);

		$head  = $this->populateTreeWithSelectStatementFromQueue(
			$queue,
			new SelectStatement($this->resolver, $begin)
		);

		if ($begin->getData()->isEmpty() == false) {
			$relation = clone current($queue);
			$relation->setFeature($begin);
			array_unshift($queue, $relation);
		}

		foreach ($queue as $relation) {
			$related = $relation->getRelatedFeature();
			if ($related->getData()->isEmpty()) {
				continue;
			}

			$expresion = new EqualExpression($relation->getJoin());
			$related->getCondition()->add($expresion, true);

			$head = new UpdateJoin(
				new UpdateStatement(
					$this->resolver,
					$related
				),
				$head
			);
		}

		return $head;
	}
}
