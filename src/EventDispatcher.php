<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Event\Map\HashMap;

trait EventDispatcher {

    /**
     * @var \Cwola\Event\EventFactory|null
     */
    protected EventFactory|null $eventFactory = null;

    /**
     * @var \Cwola\Event\Map\HashMap[]
     */
    protected array $listeners = [];


    /**
     * @param string $type
     * @param callable|\Cwola\Event\EventListener $listener
     * @param object|\Cwola\Event\EventListenOptions $options [optional]
     * @return $this
     */
    public function addEventListener(
        string $type,
        callable|EventListener $listener,
        array|EventListenOptions $options = []
    ) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            $this->listeners[$type] = new HashMap;
        }
        $listener = \is_callable($listener) ? new EventListener($listener) : $listener;
        $options = new EventListenOptions($options);
        $this->listeners[$type]->set((string)$listener->signature, [
            'listener' => $listener,
            'options' => $options
        ]);
        return $this;
    }

    /**
     * @param string $type
     * @param callable|\Cwola\Event\EventListener $listener
     * @return $this
     */
    public function removeEventListener(
        string $type,
        callable|EventListener $listener
    ) :static {
        $listener = \is_callable($listener) ? new EventListener($listener) : $listener;
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
            /** @var \Cwola\Event\EventListener */
            $listener = $info['listener'];
            /** @var \Cwola\Event\EventListenOptions */
            $options = $info['options'];
            $event = $this->createEvent($type, $options);

            if ($options->signal instanceof AbortSignal && $options->signal->aborted) {
                $this->removeEventListener(
                    $type,
                    $listener,
                    $options
                );
                return;
            }
            if ($options->once) {
                $this->removeEventListener(
                    $type,
                    $listener,
                    $options
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
     * @param \Cwola\Event\EventListenOptions $options
     * @return \Cwola\Event\Event
     */
    protected function createEvent(string $type, EventListenOptions $options) :Event {
        return $this->eventFactory()(\strtolower($type), $this, $options);
    }

    /**
     * @param void
     * @return \Cwola\Event\EventFactory
     */
    protected function eventFactory() :EventFactory {
        if ($this->eventFactory === null) {
            $this->eventFactory = new EventFactory;
        }
        return $this->eventFactory;
    }
}
