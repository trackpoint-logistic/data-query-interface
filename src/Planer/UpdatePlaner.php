<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Planer;


use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\UpdateJoin;
use Trackpoint\DataQueryInterface\Statement\UpdateStatement;
use Trackpoint\DataQueryInterface\Expression\EqualExpression;

use Psr\Log\LoggerInterface;

class UpdatePlaner extends SelectPlaner
{

	private LoggerInterface $logger;

	public function __construct(InterfaceResolver $resolver, LoggerInterface $logger)
	{
		parent::__construct($resolver, $logger);
		$this->logger = $logger;
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
