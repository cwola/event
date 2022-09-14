<?php

declare(strict_types=1);

namespace Cwola\Event\Signal;

use Cwola\Attribute\Readable;
use Cwola\Event\EventDispatcher;
use Cwola\Event\Error\AbortError;
use Cwola\Event\Factory\AbortEventFactory;

/**
 * @property bool $aborted [readonly]
 * @property string|null $reason [readonly]
 */
class AbortSignal implements Signal {

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
            throw new AbortError($this->reason ?? '');
        }
    }
}
