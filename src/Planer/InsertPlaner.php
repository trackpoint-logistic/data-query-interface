<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Planer;

use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;
use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Trackpoint\DataQueryInterface\Statement\InsertStatement;
use Trackpoint\DataQueryInterface\Statement\InsertJoin;
use Trackpoint\DataQueryInterface\Metadata\AttributeMetadata;

use Psr\Log\LoggerInterface;

use Ds\Deque;
use Ds\Queue;

class InsertPlaner implements PlanerInterface
{

	private InterfaceResolver $resolver;
	private LoggerInterface $logger;

	public function __construct(InterfaceResolver $resolver, LoggerInterface $logger)
	{
		$this->resolver = $resolver;
		$this->logger = $logger;
	}

	public function getExecutionPlan(InterfaceFeature $feature): StatementInterface
	{
		$priority = new Deque([$feature]);
		$processing = new Queue([$feature]);

		while ($processing->isEmpty() == false) {
			$feature = $processing->pop();

			$relations = $feature->getRelations();
			foreach ($relations as $relation) {

				if ($relation->isVisited() == true) {
					continue;
				}

				$relation->visited();

				$related = $relation->getRelatedFeature();

				$processing->push($related);

				$join = $relation->getJoin();

				switch ($feature->getMetadata()->getAttribute($join)->getConstrain()) {
					case AttributeMetadata::PRIMARY_CONSTRAINT:
						$feature->getReturning()->add($join);
						break;
					case AttributeMetadata::FOREIGN_CONSTRAINT:
						$feature->getData()->put($join, null);
						break;
				}

				switch ($related->getMetadata()->getAttribute($join)->getConstrain()) {
					case AttributeMetadata::PRIMARY_CONSTRAINT:
						$related->getReturning()->add($join);
						$priority->unshift($related);
						break;
					case AttributeMetadata::FOREIGN_CONSTRAINT:
						$related->getData()->put($join, null);
						$priority->push($related);
						break;
				}
			}
		}

		$head = new InsertStatement($this->resolver, $priority->shift());

		while ($priority->isEmpty() == false) {
			$head = new InsertJoin(
				new InsertStatement(
					$this->resolver,
					$priority->shift()
				),
				$head
			);
		}

		return $head;
	}
}
