<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Attribute\Readable;

/**
 * @property \Cwola\Event\AbortSignal $signal [readonly]
 */
class AbortController {

    use Readable;

    /**
     * @var \Cwola\Event\AbortSignal
     */
    #[Readable]
    protected AbortSignal $signal;


    /**
     * @param void
     */
    public function __construct() {
        $this->signal = new AbortSignal;
    }

    /**
     * @param string $reason [optional]
     * @return void
     */
    public function abort(string $reason = 'AbortError') :void {
        $this->signal->abort($reason);
    }
}
