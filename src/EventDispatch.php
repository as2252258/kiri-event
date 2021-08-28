<?php

namespace Kiri\Events;

use Annotation\Inject;
use Kiri\Abstracts\BaseObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;


/**
 *
 */
class EventDispatch extends BaseObject implements EventDispatcherInterface
{

	/**
	 * @var EventProvider
	 */
	#[Inject(EventProvider::class)]
	public EventProvider $eventProvider;


	/**
	 * @param object $event
	 * @return object
	 */
	public function dispatch(object $event): object
	{
		$lists = $this->eventProvider->getListenersForEvent($event);
		foreach ($lists as $listener) {
			if (is_array($listener) && is_string($listener[0])) {
				$listener[0] = \di(listener[0]);
			}
			/** @var Struct $list */
			$listener($event);
			if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
				break;
			}
		}
		return $event;
	}


}
