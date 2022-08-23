<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Attribute\Readable;

/**
 * @property bool $aborted [readonly]
 * @property string|null $reason [readonly]
 */
class AbortSignal implements EventTarget {

    use Readable;
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
     * @param void
     */
    public function __construct() {
        $this->eventFactory = new AbortEventFactory;
    }

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
}
