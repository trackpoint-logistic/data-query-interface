<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Expression;

use Trackpoint\DataQueryInterface\DQL;

class ExpressionBuilder
{
	public static function build(array $expression): ExpressionInterface
	{

		switch ($expression[DQL::TYPE]) {
			case DQL::EQUAL:
				$result = new EqualExpression($expression[DQL::NAME]);
				$result->setConstant($expression[DQL::CONSTANT]);
				$result->setDisjunction((bool) @$expression[DQL::DISJUNCTION]);
				return $result;
			case DQL::GREATER_EQUAL:
				$result = new GreaterEqualExpression($expression[DQL::NAME]);
				$result->setConstant($expression[DQL::CONSTANT]);
				$result->setDisjunction((bool) @$expression[DQL::DISJUNCTION]);
				return $result;
			case DQL::GREATER:
				$result = new GreaterExpression($expression[DQL::NAME]);
				$result->setConstant($expression[DQL::CONSTANT]);
				$result->setDisjunction((bool) @$expression[DQL::DISJUNCTION]);
				return $result;
			case DQL::LESS_EQUAL:
				$result = new LessEqualExpression($expression[DQL::NAME]);
				$result->setConstant($expression[DQL::CONSTANT]);
				$result->setDisjunction((bool) @$expression[DQL::DISJUNCTION]);
				return $result;
			case DQL::LESS:
				$result = new LessExpression($expression[DQL::NAME]);
				$result->setConstant($expression[DQL::CONSTANT]);
				$result->setDisjunction((bool) @$expression[DQL::DISJUNCTION]);
				return $result;
		}
	}
}
