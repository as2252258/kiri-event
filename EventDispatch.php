<?php
declare(strict_types=1);

namespace Kiri\Events;

use Kiri;
use Kiri\Abstracts\Component;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use SplPriorityQueue;


/**
 *
 */
class EventDispatch extends Component implements EventDispatcherInterface
{


	/**
	 * @param object $event
	 * @return object
     */
	public function dispatch(object $event): object
	{
		$lists = make(EventProvider::class)->getListenersForEvent($event);

		return $this->execute($lists, $event);
	}


    /**
     * @param SplPriorityQueue $lists
     * @param object $event
     * @return object
     */
	public function execute(SplPriorityQueue $lists, object $event): object
	{
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
