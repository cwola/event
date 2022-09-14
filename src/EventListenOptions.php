<?php

declare(strict_types=1);

namespace Cwola\Event;

class EventListenOptions {

    /**
     * @var bool
     */
    public bool $once = false;

    /**
     * @var bool
     */
    public bool $removable = true;

    /**
     * @var \Cwola\Event\AbortSignal|null
     */
    public AbortSignal|null $signal = null;


    /**
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     */
    public function __construct(array|EventListenOptions $options = []) {
        if (\is_array($options)) {
            if (isset($options['once'])) $this->once = !!$options['once'];
            if (isset($options['removable'])) $this->removable = !!$options['removable'];
            if (isset($options['signal'])) $this->signal = $options['signal'];
        } else {
            $this->once = $options->once;
            $this->removable = $options->removable;
            $this->signal = $options->signal;
        }
    }
}
