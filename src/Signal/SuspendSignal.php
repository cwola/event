<?php

declare(strict_types=1);

namespace Cwola\Event\Signal;

use Cwola\Attribute\Readable;
use Cwola\Event\EventDispatcher;
use Cwola\Event\Factory\SuspendEventFactory;

/**
 * @property bool $suspended [readonly]
 * @property string|null $reason [readonly]
 */
class SuspendSignal implements Signal {

    use Readable;
    use EventDispatcher;

    /**
     * @var bool
     */
    #[Readable]
    protected bool $suspended = false;

    /**
     * @var string|null
     */
    #[Readable]
    protected string|null $reason = null;


    /**
     * @param void
     */
    public function __construct() {
        $this->eventFactory = new SuspendEventFactory;
    }

    /**
     * @param string $reason [optional]
     * @return void
     */
    public function suspend(string $reason = 'Suspend') :void {
        $this->suspended = true;
        $this->reason = $reason;
        $this->dispatchEvent('suspend');
    }

    /**
     * @param void
     * @return void
     */
    public function resume() :void {
        $this->suspended = false;
        $this->reason = null;
        $this->dispatchEvent('resume');
    }
}
