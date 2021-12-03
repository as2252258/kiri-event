<?php

namespace Kiri\Events;

use Kiri\Abstracts\Component;
use Note\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;


/**
 *
 */
class EventDispatch extends Component implements EventDispatcherInterface
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
			/** @var Struct $list */
			$listener($event);
			if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
				break;
			}
		}
		return $event;
	}


}
