<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Planer;


use Trackpoint\DataQueryInterface\InterfaceFeature;
use Trackpoint\DataQueryInterface\DQL;


use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

use Trackpoint\DataQueryInterface\Statement\StatementInterface;
use Trackpoint\DataQueryInterface\Statement\SelectStatement;
use Trackpoint\DataQueryInterface\Statement\LeftJoin;
use Trackpoint\DataQueryInterface\Statement\InnerJoin;

use Psr\Log\LoggerInterface;

use Ds\Queue;

class SelectPlaner implements PlanerInterface
{

	protected InterfaceResolver $resolver;
	protected LoggerInterface $logger;

	public function __construct(InterfaceResolver $resolver, LoggerInterface $logger)
	{
		$this->resolver = $resolver;
		$this->logger = $logger;
	}

	protected function getExecutionQueue(InterfaceFeature $feature): array
	{

		$result = [];

		$relations = $feature->getRelations();
		foreach ($relations as $relation) {

			if ($relation->isVisited() == true) {
				continue;
			}

			$relation->visited();
			$result[] = $relation;

			$related = $relation->getRelatedFeature();

			$join = $relation->getJoin();

			$feature->getReturning()->add($join);
			$related->getReturning()->add($join);

			$result = array_merge(
				$result,
				$this->getExecutionQueue($related)
			);
		}

		return $result;
	}

	/**
	 * The method returns the feature with the most conditions.
	 */
	protected function getEntryFeature(InterfaceFeature $feature): InterfaceFeature
	{

		$depth  = $feature->getCondition()->depth();
		$result = $feature;

		$relations = [];

		$queue = new Queue($feature->getRelations());

		while ($queue->isEmpty() == false) {

			$relation = $queue->pop();
			if ($relation->isVisited() == true) {
				continue;
			}

			$relation->visited();
			$relations[] = $relation;

			$related = $relation->getRelatedFeature();
			$queue->push(...$related->getRelations());

			if ($related->getCondition()->depth() > $depth) {
				$depth = $related->getCondition()->depth();
				$result = $related;
			}
		}

		foreach ($relations as $relation) {
			$relation->reset();
		}

		return $result;
	}

	/**
	 * Create SelectStatement tree
	 */
	protected function populateTreeWithSelectStatementFromQueue(array $queue, StatementInterface $head): StatementInterface
	{
		foreach ($queue as $relation) {
			$feature = $relation->getRelatedFeature();

			$left = new SelectStatement(
				$this->resolver,
				$feature
			);

			if ($relation->getType() == DQL::INNER_JOIN) {
				$head = new InnerJoin(
					$left,
					$head,
					$relation->getJoin()
				);
			} else if ($relation->getType() == DQL::LEFT_JOIN) {
				$head = new LeftJoin(
					$left,
					$head,
					$relation->getJoin()
				);
			}
		}

		return $head;
	}


	public function getExecutionPlan(InterfaceFeature $feature): StatementInterface
	{
		$feature = $this->getEntryFeature($feature);
		$queue = $this->getExecutionQueue($feature);

		$head = new SelectStatement(
			$this->resolver,
			$feature
		);

		return $this->populateTreeWithSelectStatementFromQueue($queue, $head);
	}
}
