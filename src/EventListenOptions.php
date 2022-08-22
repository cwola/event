<?php

declare(strict_types=1);

namespace Cwola\Event;

class EventListenOptions {

    /**
     * @var bool
     */
    public bool $once = false;


    /**
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     */
    public function __construct(array|EventListenOptions $options = []) {
        if (\is_array($options)) {
            $this->once = isset($options['once']) ? !!$options['once'] : false;
        } else {
            $this->once = $options->once;
        }
    }
}
