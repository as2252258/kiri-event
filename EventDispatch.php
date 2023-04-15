<?php
declare(strict_types=1);

namespace Kiri\Events;

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
	 * @param EventProvider $eventProvider
	 * @param array $config
	 * @throws \Exception
	 */
	public function __construct(public EventProvider $eventProvider, array $config = [])
	{
		parent::__construct($config);
	}


	/**
	 * @param object $event
	 * @return object
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function dispatch(object $event): object
	{
		$lists = $this->eventProvider->getListenersForEvent($event);
		if (!$lists->valid()) {
			return $event;
		}
		$lists->top();
		while ($lists->valid()) {
			try {
				call_user_func($lists->current(), $event);
			} catch (\Throwable $exception) {
				$this->logger->error($exception->getMessage(), [$exception]);
			}
			if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
				break;
			}
			$lists->next();
		}
		return $event;
	}


}
