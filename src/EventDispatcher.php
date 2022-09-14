<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Event\Map\HashMap;
use Cwola\Event\Factory\EventFactory;
use Cwola\Event\Listener;
use Cwola\Event\Signal;

trait EventDispatcher {

    /**
     * @var \Cwola\Event\Factory\EventFactory|null
     */
    protected EventFactory|null $eventFactory = null;

    /**
     * @var \Cwola\Event\Map\HashMap[]
     */
    protected array $listeners = [];


    /**
     * @param string $type
     * @param callable|\Cwola\Event\Listener\EventListener $listener
     * @param object|\Cwola\Event\Listener\Options $options [optional]
     * @return $this
     */
    public function addEventListener(
        string $type,
        callable|Listener\EventListener $listener,
        array|Listener\Options $options = []
    ) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            $this->listeners[$type] = new HashMap;
        }
<<<<<<< HEAD
        $listener = ($listener instanceof Listener\EventListener) ? $listener : new Listener\EventListener($listener);
        $options = new Listener\Options($options);
=======
        $listener = ($listener instanceof EventListener) ? $listener : new EventListener($listener);
        $options = new EventListenOptions($options);
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
        $this->listeners[$type]->set((string)$listener->signature, [
            'listener' => $listener,
            'options' => $options
        ]);
        return $this;
    }

    /**
     * @param string $type
<<<<<<< HEAD
     * @param callable|\Cwola\Event\Listener\EventListener $listener
=======
     * @param callable|\Cwola\Event\EventListener $listener
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
     * @return $this
     */
    public function removeEventListener(
        string $type,
<<<<<<< HEAD
        callable|Listener\EventListener $listener
    ) :static {
        $listener = ($listener instanceof Listener\EventListener) ? $listener : new Listener\EventListener($listener);
=======
        callable|EventListener $listener
    ) :static {
        $listener = ($listener instanceof EventListener) ? $listener : new EventListener($listener);
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
        $signature = (string)$listener->signature;
        if (
            $this->listeners[$type]->has($signature)
            && $this->listeners[$type]->get($signature)['options']->removable
        ) {
            $this->listeners[$type]->unset($signature);
        }
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function dispatchEvent(string $type) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return $this;
        }
        $this->listeners[$type]->forEach(function($signature, $info, $type) {
            /** @var \Cwola\Event\Listener\EventListener */
            $listener = $info['listener'];
            /** @var \Cwola\Event\Listener\Options */
            $options = $info['options'];
            $event = $this->createEvent($type, $options);

<<<<<<< HEAD
            if (
                $options->signal instanceof Signal\Signal
                && $this->signalHandling($options->signal, $type, $listener, $options, $event) === false
            ) {
=======
            if ($options->signal instanceof AbortSignal && $options->signal->aborted) {
                $options->removable = true;  // forced.
                $this->removeEventListener(
                    $type,
                    $listener
                );
>>>>>>> 204ed34e53b760139c3e20c1bf1c811a01f2fa18
                return;
            }
            if ($options->once) {
                $options->removable = true;  // forced.
                $this->removeEventListener(
                    $type,
                    $listener
                );
            }
            $listener->handleEvent($event);
            if (!$event->defaultPrevented) {
                $event->handleDefault();
            }
            if ($event->propagationStoped) {
                return false;
            }
        }, $type);
        return $this;
    }

    /**
     * @param string $type
     * @param \Cwola\Event\Listener\Options $options
     * @return \Cwola\Event\Event
     */
    protected function createEvent(string $type, Listener\Options $options) :Event {
        return $this->eventFactory()(\strtolower($type), $this, $options);
    }

    /**
     * @param void
     * @return \Cwola\Event\Factory\EventFactory
     */
    protected function eventFactory() :Factory\EventFactory {
        if ($this->eventFactory === null) {
            $this->eventFactory = new Factory\EventFactory;
        }
        return $this->eventFactory;
    }

    /**
     * @param \Cwola\Event\Signal\Signal $signal
     * @param string $type
     * @param \Cwola\Event\Listener\EventListener $listener
     * @param \Cwola\Event\Listener\Options $options
     * @param \Cwola\Event\Event $event
     * @return bool
     */
    protected function signalHandling(
        Signal\Signal $signal,
        string $type,
        Listener\EventListener $listener,
        Listener\Options $options,
        Event $event
    ) :bool {
        if ($signal instanceof Signal\AbortSignal && $signal->aborted) {
            $options->removable = true;  // forced.
            $this->removeEventListener(
                $type,
                $listener
            );
            return false;
        } else if ($signal instanceof Signal\SuspendSignal && $signal->suspended) {
            return false;
        }
        return true;
    }
}
