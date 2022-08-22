<?php

declare(strict_types=1);

namespace Cwola\Event;

class EventListenOptions {

    /**
     * @var bool
     */
    public bool $once = false;

    /**
     * @var \Cwola\Event\AbortSignal|null
     */
    public AbortSignal|null $signal = null;


    /**
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     */
    public function __construct(array|EventListenOptions $options = []) {
        if (\is_array($options)) {
            $this->once = isset($options['once']) ? !!$options['once'] : false;
            $this->signal = isset($options['signal']) ? $options['signal'] : null;
        } else {
            $this->once = $options->once;
            $this->signal = $options->signal;
        }
    }
}
