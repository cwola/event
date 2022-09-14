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
<<<<<<< HEAD
     * @param callable|\Cwola\Event\Listener\EventListener $listener
=======
     * @param callable|\Cwola\Event\EventListener $listener
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
     * @return mixed
     */
    public function removeEventListener(
        string $type,
<<<<<<< HEAD
        callable|Listener\EventListener $listener
=======
        callable|EventListener $listener
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
    );

    /**
     * @param string $type
     * @return mixed
     */
    public function dispatchEvent(string $type);
}
