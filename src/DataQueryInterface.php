<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

use Trackpoint\DataQueryInterface\Planer\PlanerInterface;
use Trackpoint\DataQueryInterface\Planer\SelectPlaner;
use Trackpoint\DataQueryInterface\Planer\UpdatePlaner;
use Trackpoint\DataQueryInterface\Planer\InsertPlaner;


use Trackpoint\DataQueryInterface\Executor\ExecutorInterface;
use Trackpoint\DataQueryInterface\Executor\SelectExecutor;
use Trackpoint\DataQueryInterface\Executor\UpdateExecutor;
use Trackpoint\DataQueryInterface\Executor\InsertExecutor;


use Psr\Log\LoggerInterface;

use Generator;



class DataQueryInterface
{

	private InterfaceResolver $resolver;
	private LoggerInterface $logger;

	public function __construct(InterfaceResolver $resolver, LoggerInterface $logger)
	{
		$this->resolver = $resolver;
		$this->logger = $logger;
	}

	private function getPlaner($statement): PlanerInterface
	{
		switch ($statement) {
			case DQL::SELECT:
				return new SelectPlaner($this->resolver, $this->logger);
			case DQL::UPDATE:
				return new UpdatePlaner($this->resolver, $this->logger);
			case DQL::INSERT:
				return new InsertPlaner($this->resolver, $this->logger);
		}
	}

	private function getExecutor($statement): ExecutorInterface
	{
		switch ($statement) {
			case DQL::SELECT:
				return new SelectExecutor($this->logger);
			case DQL::UPDATE:
				return new UpdateExecutor($this->logger);
			case DQL::INSERT:
				return new InsertExecutor($this->logger);
		}
	}

	public function execute(array $request): Generator
	{
		$parser = new RequestParser($this->resolver, $this->logger);

		list($statement, $feature) = $parser->parse($request);

		$planer = $this->getPlaner($statement);

		$plan = $planer->getExecutionPlan($feature);

		$executor = $this->getExecutor($statement);

		yield from $executor->execute($plan);
	}
}
