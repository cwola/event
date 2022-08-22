<?php

declare(strict_types=1);

namespace Cwola\Event;

use Cwola\Event\Map\HashMap;

trait EventDispatcher {

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
        $sameListener = $this->getSameListener(
            $type,
            $listener,
            $options
        );

        if (!($sameListener instanceof EventListener)) {
            $this->listeners[$type]->set((string)$listener->signature, [
                'listener' => $listener,
                'options' => $options
            ]);
        }
        return $this;
    }

    /**
     * @param string $type
     * @param callable|\Cwola\Event\EventListener $listener
     * @param object|\Cwola\Event\EventListenOptions $options [optional]
     * @return $this
     */
    public function removeEventListener(
        string $type,
        callable|EventListener $listener,
        array|EventListenOptions $options = []
    ) :static {
        $listener = \is_callable($listener) ? new EventListener($listener) : $listener;
        $options = new EventListenOptions($options);
        $sameListener = $this->getSameListener(
            $type,
            $listener,
            $options
        );
        if ($sameListener instanceof EventListener) {
            $this->listeners[$type]->unset((string)$sameListener->signature);
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
            
            $listener->handleEvent($event);
            if ($event instanceof OnceEvent) {
                $this->removeEventListener(
                    $type,
                    $listener,
                    $options
                );
            }
            if ($event->propagationStoped) {
                return false;
            }
        }, $type);
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function clearEvent(string $type) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return $this;
        }
        unset($this->listeners[$type]);
        return $this;
    }

    /**
     * @param string $type
     * @param \Cwola\Event\EventListenOptions $options
     * @return \Cwola\Event\Event
     */
    protected function createEvent(string $type, EventListenOptions $options) :Event {
        $type = \strtolower($type);
        return EventFactory::create(
            $type,
            $this,
            $options
        );
    }

    /**
     * @param string $type
     * @param \Cwola\Event\EventListener $listener
     * @param \Cwola\Event\EventListenOptions $options
     * @return \Cwola\Event\EventListener|null
     */
    protected function getSameListener(string $type, EventListener $listener, EventListenOptions $options) :EventListener|null {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return null;
        }

        if (($info = $this->listeners[$type]->get((string)$listener->signature)) !== null) {
            // ignore options->once option.
            return $info['listener'];
        }
        return null;
    }
}
