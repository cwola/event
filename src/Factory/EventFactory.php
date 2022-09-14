<?php

declare(strict_types=1);

namespace Cwola\Event\Factory;

use Cwola\Event\EventTarget;
use Cwola\Event\Listener\Options;
use Cwola\Event\Event;

class EventFactory {

    /**
     * @param string $type
     * @param \Cwola\Event\EventTarget $target
     * @param array|\Cwola\Event\Listener\Options $options [optional]
     * @return \Cwola\Event\Event
     *
     * @see EventFactory::create()
     */
    public function __invoke(
        string $type,
        EventTarget $target,
        array|Options $options = []
    ) :Event {
        return static::create($type, $target, $options);
    }

    /**
     * @param string $type
     * @param \Cwola\Event\EventTarget $target
     * @param array|\Cwola\Event\Listener\Options $options [optional]
     * @return \Cwola\Event\Event
     */
    public static function create(
        string $type,
        EventTarget $target,
        array|Options $options = []
    ) :Event {
        return new Event(
            $type,
            $target
        );
    }
}
