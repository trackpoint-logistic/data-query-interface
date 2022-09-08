<?php

declare(strict_types=1);

namespace Trackpoint\DataQueryInterface\DataStructure;

use Generator;
use IteratorAggregate;
use Ds\Queue;

class BinarySearchTree implements IteratorAggregate
{
	private $root = null;
	private string $key;

	public function __construct(string $key)
	{
		$this->key = $key;
	}


	public function has($index)
	{
		$node = $this->root;

		while ($node) {
			if ($index > $node->index) {
				$node = $node->right;
			} elseif ($index < $node->index) {
				$node = $node->left;
			} else {
				return true;
			}
		}

		return false;
	}

	public function insert($index)
	{
		$node = $this->root;

		if ($node == null) {
			$this->root = new Node($index);
			return;
		}

		while ($node) {
			if ($index > $node->index) {
				if ($node->right) {
					$node = $node->right;
				} else {
					$node->right = new Node($index);
					break;
				}
			} elseif ($index < $node->index) {
				if ($node->left) {
					$node = $node->left;
				} else {
					$node->left = new Node($index);
					break;
				}
			} else {
				break;
			}
		}

		return;
	}

	public function fill($entity)
	{

		foreach ($entity as $tuple) {
			$this->insert($tuple[$this->key]);
		}

		return $this;
	}

	public function getIterator()
	{

		$queue = new Queue();
		$queue->push($this->root);

		while ($queue->isEmpty() == false) {
			$node = $queue->pop();

			if ($node->left) {
				$queue->push($node->left);
			}

			if ($node->right) {
				$queue->push($node->right);
			}

			yield $node->tuple;
		}
	}
}
