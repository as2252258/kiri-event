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


/**
 *
 */
class EventDispatch extends Component implements EventDispatcherInterface
{


	/**
	 * @param object $event
	 * @return object
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface|\ReflectionException
	 */
	public function dispatch(object $event): object
	{
		$lists = Kiri::getDi()->get(EventProvider::class)->getListenersForEvent($event);
		if (!$lists->valid()) {
			return $event;
		}
		$lists->top();
		while ($lists->valid()) {
			try {
				call_user_func($lists->current(), $event);
			} catch (\Throwable $exception) {
				error($exception->getMessage(), [$exception]);
			}
			if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
				break;
			}
			$lists->next();
		}
		return $event;
	}


}
