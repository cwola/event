<?php

declare(strict_types=1);

namespace Cwola\Event;

interface EventTarget {

    /**
     * @param string $type
     * @param callable|\Cwola\Event\EventListener $listener
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     * @return mixed
     */
    public function addEventListener(
        string $type,
        callable|EventListener $listener,
        array|EventListenOptions $options = []
    );

    /**
     * @param string $type
     * @param callable|\Cwola\Event\EventListener $listener
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     * @return mixed
     */
    public function removeEventListener(
        string $type,
        callable|EventListener $listener,
        array|EventListenOptions $options = []
    );

    /**
     * @param string $type
     * @return mixed
     */
    public function dispatchEvent(string $type);
}
