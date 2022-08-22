<?php

declare(strict_types=1);

namespace Cwola\Event;

class AbortEventFactory extends EventFactory {

    /**
     * {@inheritDoc}
     */
    public static function create(
        string $type,
        EventTarget $target,
        array|EventListenOptions $options = []
    ) :Event {
        if ($type === 'abort') {
            return new AbortEvent(
                $type,
                $target
            );
        }
        return parent::create($type, $target, $options);
    }
}
