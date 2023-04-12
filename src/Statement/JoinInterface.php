<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\Statement;

interface JoinInterface extends StatementInterface
{

	public function getLeftNode(): StatementInterface;
	public function getRightNode(): StatementInterface;
	public function getRelationKey(): string;
}
