<?php

declare(strict_types=1);

namespace Cwola\Event\Signal\Controller;

use Cwola\Attribute\Readable;
use Cwola\Event\Signal\SuspendSignal;

/**
 * @property \Cwola\Event\Signal\SuspendSignal $signal [readonly]
 */
class SuspendController {

    use Readable;

    /**
     * @var \Cwola\Event\Signal\SuspendSignal
     */
    #[Readable]
    protected SuspendSignal $signal;


    /**
     * @param void
     */
    public function __construct() {
        $this->signal = new SuspendSignal;
    }

    /**
     * @param string $reason [optional]
     * @return void
     */
    public function suspend(string $reason = 'Suspend') :void {
        $this->signal->suspend($reason);
    }

    /**
     * @param void
     * @return void
     */
    public function resume() :void {
        $this->signal->resume();
    }
}
