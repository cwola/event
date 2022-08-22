<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Attribute\Readable;

/**
 * @property bool $aborted [readonly]
 * @property string|null $reason [readonly]
 */
class AbortSignal implements EventTarget {

    use EventDispatcher;

    /**
     * @var bool
     */
    #[Readable]
    protected bool $aborted = false;

    /**
     * @var string|null
     */
    #[Readable]
    protected string|null $reason = null;


    /**
     * @param string $reason [optional]
     * @return void
     */
    public function abort(string $reason = 'AbortError') :void {
        $this->aborted = true;
        $this->reason = $reason;
        $this->dispatchEvent('abort');
    }

    /**
     * @param void
     * @return void
     *
     * @throws \Cwola\Event\Error\AbortError
     */
    public function throwIfAborted() :void {
        if ($this->aborted) {
            throw new Error\AbortError($this->reason ?? '');
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function createEvent(string $type, EventListenOptions $options) :Event {
        $type = \strtolower($type);
        return AbortEventFactory::create(
            $type,
            $this,
            $options
        );
    }
}
