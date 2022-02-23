<?php

namespace Kiri\Events;

use Kiri\Abstracts\Component;
use Kiri;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function dispatch(object $event): object
	{
		$lists = $this->getEventProvider()->getListenersForEvent($event);
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
