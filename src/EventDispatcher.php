<?php

declare(strict_types=1);

namespace Cwola\Event;

trait EventDispatcher {

    /**
     * @var array
     *
     * [
     *     $eventType => [
     *         'priority' => [
     *             &$listenersElement, &$listenersElement, &$listenersElement ...
     *         ],
     *         'listeners' => [
     *             $signature => [
     *                 'listener' => $listener,
     *                 'options' => $options,
     *                 'priority' => index of 'priority' element
     *             ], ...
     *         ]
     *     ], ...
     * ]
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
            $this->listeners[$type] = [
                'priority' => [],
                'listeners' => []
            ];
        }

        $listener = \is_callable($listener) ? new EventListener($listener) : $listener;
        $options = new EventListenOptions($options);
        list($index, $sameListener) = $this->getSameListener(
            $type,
            $listener,
            $options
        );

        if (!($sameListener instanceof EventListener)) {
            $priority = \count($this->listeners[$type]['priority']);
            $this->listeners[$type]['listeners'][$listener->signature] = [
                'listener' => $listener,
                'options' => $options,
                'priority' => $priority
            ];
            $this->listeners[$type]['priority'][$priority] = &$this->listeners[$type]['listeners'][$listener->signature];
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
        array|EventListenOptions $options = null
    ) :static {
        list($index, $sameListener) = $this->getSameListener(
            $type,
            \is_callable($listener) ? new EventListener($listener) : $listener,
            new EventListenOptions($options)
        );
        if ($sameListener instanceof EventListener) {
            \array_splice($this->listeners[$type]['priority'], $index, 1);
            unset($this->listeners[$type]['listeners'][$listener->signature]);
        }
        return $this;
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
        foreach ($this->listeners[$type]['priority'] as $info) {
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

    /**
     * @param string $type
     * @param \Cwola\Event\EventListener $listener
     * @param \Cwola\Event\EventListenOptions $options
     * @return array [$index, $listener] or [null, null]
     */
    protected function getSameListener(string $type, EventListener $listener, EventListenOptions $options) :array {
        $type = \strtolower($type);
        if (!isset($this->listeners[$type])) {
            return [null, null];
        }

        if (isset($this->listeners[$type]['listeners'][$listener->signature])) {
            // ignore options->once option.
            return [
                $this->listeners[$type]['listeners'][$listener->signature]['priority'],
                $this->listeners[$type]['listeners'][$listener->signature]['listener']
            ];
        }
        return [null, null];
    }
}
