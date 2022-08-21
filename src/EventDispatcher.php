<?php

declare(strict_types=1);

namespace Cwola\Event;

trait EventDispatcher {

    /**
     * @param array
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
        array|EventListenOptions $options = null
    ) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            $this->listeners[$type] = [];
        }
        $this->listener[$type][] = [
            'listener' => \is_callable($listener) ? new EventListener($listener) : $listener,
            'options' => new EventListenOptions($options)
        ];
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
        array|EventListenOptions $options = null
    ) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return $this;
        }
        // @todo
    }

    /**
     * @param string $type
     * @return $this
     */
    public function fireEvent(string $type) :static {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return $this;
        }
        foreach ($this->listeners[$type] as $info) {
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
                break;
            }
        }
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
}
