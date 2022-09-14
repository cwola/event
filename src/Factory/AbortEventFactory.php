<?php

declare(strict_types=1);

namespace Cwola\Event\Factory;

use Cwola\Event\EventTarget;
use Cwola\Event\Listener\Options;
use Cwola\Event\Event\AbortEvent;

class AbortEventFactory extends EventFactory {

    /**
     * {@inheritDoc}
     */
    public static function create(
        string $type,
        EventTarget $target,
        array|Options $options = []
    ) :AbortEvent {
        if ($type === 'abort') {
            return new AbortEvent(
                $type,
                $target
            );
        }
        return parent::create($type, $target, $options);
    }
}
