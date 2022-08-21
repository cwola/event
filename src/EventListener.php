<?php

declare(strict_types=1);

namespace Cwola\Event;

class EventListener {

    /**
     * @var callable
     */
    protected /* callable */ $listener;


    /**
     * @param callable $listener
     */
    public function __construct(callable $listener) {
        $this->listener = $listener;
    }

    /**
     * @param \Cwola\Event\Event $event
     * @param ...$args
     * @return void
     */
    public function handleEvent(Event $event, ...$args) :void {
        ($this->listener)($event, ...$args);
    }
}
