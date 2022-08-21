<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Attribute\Readable;

/**
 * @property \Cwola\Event\CallableSignature $signature [readonly]
 */
class EventListener {

    /**
     * @var \Cwola\Event\CallableSignature
     */
    #[Readable]
    protected CallableSignature $signature;

    /**
     * @var callable
     */
    protected /* callable */ $listener;


    /**
     * @param callable $listener
     */
    public function __construct(callable $listener) {
        $this->signature = new CallableSignature($listener);
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
