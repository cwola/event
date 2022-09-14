<?php

declare(strict_types=1);

namespace Cwola\Event\Factory;

use Cwola\Event\EventTarget;
use Cwola\Event\Listener\Options;
use Cwola\Event\Event\SuspendEvent;

class SuspendEventFactory extends EventFactory {

    /**
     * {@inheritDoc}
     */
    public static function create(
        string $type,
        EventTarget $target,
        array|Options $options = []
    ) :SuspendEvent {
        if ($type === 'suspend' || $type === 'resume') {
            return new SuspendEvent(
                $type,
                $target
            );
        }
        return parent::create($type, $target, $options);
    }
}
