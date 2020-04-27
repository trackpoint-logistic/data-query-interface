<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface;

use Trackpoint\DataQueryInterface\Resolver\InterfaceResolver;

use Trackpoint\DataQueryInterface\Expression\ExpressionBuilder;

use Psr\Log\LoggerInterface;

use Ds\Map;
use Ds\Set;
use Exception;


class RequestParser
{

	private InterfaceResolver $resolver;
	private LoggerInterface $logger;

	public function __construct(InterfaceResolver $resolver, LoggerInterface $logger)
	{
		$this->resolver = $resolver;
		$this->logger = $logger;
	}

	private function getExpressions(array $expressions)
	{
		$result = new Map();

		foreach ($expressions as $raw) {
			$expression = ExpressionBuilder::build($raw);
			$result->put($expression->getName(), $expression);
		}

		return $result;
	}

	private function createFeatureRelation(InterfaceFeature $feature, InterfaceFeature $related, array $relation): void
	{
		$join = $relation[DQL::JOIN];
		$type = $relation[DQL::TYPE] ?? DQL::OUTER_JOIN;

		$visited = false;
		$feature->getRelations()->add(new FeatureRelation($related, $visited, $join, $type));
		$related->getRelations()->add(new FeatureRelation($feature, $visited, $join, $type));
	}

	private function createFeature(array $interface, Map $expressions, Set $returning, Map $data): InterfaceFeature
	{
		$metadata   = $this->resolver->getInterfaceMetadata($interface[DQL::NAME]);

		$feature = new InterfaceFeature($interface[DQL::NAME], $metadata);

		foreach ($metadata->getAttributes() as $attribute_name => $attribute_metadata) {

			if (($expression = $expressions->get($attribute_name, null)) != null) {
				$feature->getCondition()->add($expression);
			}

			if ($returning->contains($attribute_name)) {
				$feature->getReturning()->add($attribute_name);
			}

			if ($data->hasKey($attribute_name)) {
				$feature->getData()->put(
					$attribute_name,
					$data->get($attribute_name)
				);
			}
		}

		foreach ($interface[DQL::RELATION] ?? [] as $relation) {
			$join = $relation[DQL::JOIN];

			if ($feature->getMetadata()->getAttributes()->hasKey($join) == false) {
				throw new Exception('Relation exception');
			}

			$related = $this->createFeature(
				$relation[DQL::INTERFACE],
				$expressions,
				$returning,
				$data
			);

			if ($related->getMetadata()->getAttributes()->hasKey($join) == false) {
				throw new Exception('Relation exception');
			}

			$this->createFeatureRelation(
				$feature,
				$related,
				$relation
			);
		}

		return $feature;
	}

	public function parse(array $request): array
	{
		$statement   = $request[DQL::STATEMENT];

		$expressions = $this->getExpressions($request[DQL::EXPRESSIONS]);
		$returning   = new Set($request[DQL::RETURNING] ?? []);
		$data        = new Map($request[DQL::DATA] ?? []);

		$feature = $this->createFeature(
			$request[DQL::INTERFACE],
			$expressions,
			$returning,
			$data
		);

		return [$statement, $feature];
	}
}
