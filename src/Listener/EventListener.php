<?php

declare(strict_types=1);

namespace Cwola\Event\Listener;

use Cwola\Attribute\Readable;
use Cwola\Event\Signature\CallableSignature;
use Cwola\Event\Event;

/**
 * @property \Cwola\Event\Signature\CallableSignature $signature [readonly]
 */
class EventListener {

    use Readable;

    /**
     * @var \Cwola\Event\Signature\CallableSignature
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
