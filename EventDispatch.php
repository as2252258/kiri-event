<?php

namespace Kiri\Events;

use Kiri\Abstracts\Component;
use Kiri\Kiri;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;


/**
 *
 */
class EventDispatch extends Component implements EventDispatcherInterface
{


	/**
	 * @param object $event
	 * @return object
	 * @throws \ReflectionException
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
