<?php

declare(strict_types=1);

namespace Cwola\Event\Signal\Controller;

use Cwola\Attribute\Readable;
use Cwola\Event\Signal\AbortSignal;

/**
 * @property \Cwola\Event\Signal\AbortSignal $signal [readonly]
 */
class AbortController {

    use Readable;

    /**
     * @var \Cwola\Event\Signal\AbortSignal
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
