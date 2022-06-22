<?php

namespace Kiri\Events;


use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

/**
 *
 */
class EventProvider implements ListenerProviderInterface
{

	/** @var Struct[] */
	private array $_listeners = [];


	/**
	 * @param object $event
	 * @return SplPriorityQueue
	 */
	public function getListenersForEvent(object $event): SplPriorityQueue
	{
		$queue = new SplPriorityQueue();
		$queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
		// TODO: Implement getListenersForEvent() method.
		foreach ($this->_listeners[get_class($event)] ?? [] as $listener) {
			$queue->insert($listener->listener, $listener->priority);
		}
		return $queue;
	}


	/**
	 * @param string $event
	 * @param array|\Closure|string $handler
	 * @param int $zOrder
	 * @throws \Exception
	 */
	public function on(string $event, array|\Closure|string $handler, int $zOrder = 1)
	{
		if (is_string($handler) && !is_callable($handler, true)) {
			throw new \Exception('Event handler must is execute function.');
		}
		$this->_listeners[$event][] = new Struct($event, $handler, $zOrder);
	}


	/**
	 * @param string $event
	 * @param callable $handler
	 * @return void
	 */
	public function off(string $event, callable $handler): void
	{
		$events = $this->_listeners[$event] ?? [];

		$this->_listeners[$event] = array_filter($events, function ($value) use ($handler) {
			return $value->listener !== $handler;
		});
	}


}
