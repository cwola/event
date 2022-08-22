<?php

declare(strict_types=1);

namespace Cwola\Event;

class EventFactory {

    /**
     * @param string $type
     * @param \Cwola\Event\EventTarget $target
     * @param array|\Cwola\Event\EventListenOptions $options [optional]
     * @return \Cwola\Event\Event
     */
    public static function create(
        string $type,
        EventTarget $target,
        array|EventListenOptions $options = []
    ) :Event {
        $options = new EventListenOptions($options);
        $event = 'Event';
        if ($options->once) {
            $event = 'OnceEvent';
        }
        return (new ('Cwola\\Event\\' . $event)(
            $type,
            $target
        ));
    }
}
