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

use Generator;

class DataQueryInterface
{

	private InterfaceResolver $resolver;

	public function __construct(InterfaceResolver $resolver)
	{
		$this->resolver = $resolver;
	}

	private function getPlaner($statement): PlanerInterface
	{
		switch ($statement) {
			case DQL::SELECT:
				return new SelectPlaner($this->resolver);
			case DQL::UPDATE:
				return new UpdatePlaner($this->resolver);
			case DQL::INSERT:
				return new InsertPlaner($this->resolver);
		}
	}

	private function getExecutor($statement): ExecutorInterface
	{
		switch ($statement) {
			case DQL::SELECT:
				return new SelectExecutor();
			case DQL::UPDATE:
				return new UpdateExecutor();
			case DQL::INSERT:
				return new InsertExecutor();
		}
	}

	public function test(array $request)
	{
		$parser = new RequestParser($this->resolver);

		list($statement, $feature) = $parser->parse($request);

		$planer = $this->getPlaner($statement);

		$plan = $planer->getExecutionPlan($feature);

		$executor = $this->getExecutor($statement);

		return;
	}

	public function execute(array $request): Generator
	{
		$parser = new RequestParser($this->resolver);

		list($statement, $feature) = $parser->parse($request);

		$planer = $this->getPlaner($statement);

		$plan = $planer->getExecutionPlan($feature);

		$executor = $this->getExecutor($statement);

		yield from $executor->execute($plan);
	}
}
