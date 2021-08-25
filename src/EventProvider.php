<?php

namespace Kiri\Events;


use Psr\EventDispatcher\ListenerProviderInterface;

/**
 *
 */
class EventProvider implements ListenerProviderInterface
{

	/** @var Struct[] */
	private array $_listeners = [];


	/**
	 * @param object $event
	 * @return iterable
	 */
	public function getListenersForEvent(object $event): iterable
	{
		$queue = new \SplPriorityQueue();
		// TODO: Implement getListenersForEvent() method.
		foreach ($this->_listeners[get_class($event)] ?? [] as $listener) {
			$queue->insert($listener->listener, $listener->priority);
		}
		return $queue;
	}


	/**
	 * @param string $event
	 * @param callable $handler
	 * @param int $zOrder
	 */
	public function on(string $event, callable $handler, int $zOrder = 1)
	{
		$this->_listeners[$event][] = new Struct($event, $handler, $zOrder);
	}


}
