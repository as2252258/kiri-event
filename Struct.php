<?php

declare(strict_types=1);

namespace Kiri\Events;

/**
 *
 */
class Struct
{

	public string $event;

	public array|\Closure|string $listener;

	public int $priority;

	public function __construct(string $event, array|\Closure|string $listener, int $priority)
	{
		$this->event = $event;
		$this->listener = $listener;
		$this->priority = $priority;
	}
}
