<?php

declare(strict_types=1);

namespace Cwola\Event;

interface EventTarget {

    /**
     * @param string $type
     * @param callable|\Cwola\Event\Listener\EventListener $listener
     * @param array|\Cwola\Event\Listener\Options $options [optional]
     * @return mixed
     */
    public function addEventListener(
        string $type,
        callable|Listener\EventListener $listener,
        array|Listener\Options $options = []
    );

    /**
     * @param string $type
     * @param callable|\Cwola\Event\Listener\EventListener $listener
     * @return mixed
     */
    public function removeEventListener(
        string $type,
        callable|Listener\EventListener $listener
    );

    /**
     * @param string $type
     * @return mixed
     */
    public function dispatchEvent(string $type);
}
