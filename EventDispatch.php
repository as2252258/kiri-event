<?php
declare(strict_types=1);

namespace Kiri\Events;

use Exception;
use Kiri;
use Kiri\Abstracts\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use ReflectionException;


/**
 *
 */
class EventDispatch extends Component implements EventDispatcherInterface
{


	/**
	 * @param object $event
	 * @return object
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface|ReflectionException
	 */
	public function dispatch(object $event): object
	{
		$lists = make(EventProvider::class)->getListenersForEvent($event);
		if ($lists->isEmpty()) {
			return $event;
		}
        foreach ($lists as $item)  {
            try {
                call_user_func($item, $event);
            } catch (\Throwable $exception) {
                error($exception);
            }
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }
		return $event;
	}


}
