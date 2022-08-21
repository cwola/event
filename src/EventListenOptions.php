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
            $options = new self($options);
        }
        $this->once = $options->once;
    }
}
