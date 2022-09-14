<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

use Cwola\Event;

class FooEngineBootedEvent extends Event\Event {
    /**
     * {@inheritDoc}
     */
    protected bool $cancelable = false;

    /**
     * {@inheritDoc}
     */
    public function handleDefault(): void {
        echo 'Installation Engine completed all steps successfully.' . PHP_EOL;
    }
}

class FooEngineEventFactory extends Event\Factory\EventFactory {
    /**
     * {@inheritDoc}
     */
    public static function create(
        string $type,
        Event\EventTarget $target,
        array|Event\Listener\Options $options = []
    ) :Event\Event {
        if ($type === 'booted') {
            return new FooEngineBootedEvent(
                $type,
                $target
            );
        }
        return parent::create($type, $target, $options);
    }
}

class FooEngine implements Event\EventTarget {
    use Event\EventDispatcher;

    public function __construct() {
        $this->eventFactory = new FooEngineEventFactory;
    }

    public function boot() {
        $this->dispatchEvent('beforeBoot');

        echo 'boot process... wait 10 sec...' . PHP_EOL;
        sleep(10);

        $this->dispatchEvent('booted');
    }
}

$engine = new FooEngine;
$engine->addEventListener('beforeBoot', function(Event\Event $event) {
    echo sprintf('%s : start boot method.', $event->timeStamp) . PHP_EOL;
});
$engine->addEventListener('booted', function(Event\Event $event) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
    $event->preventDefault();
    // 'cancelable' property of 'FooEngineBootedEvent' is false,
    // the default behavior cannot be prevented.
    //
});


$engine->boot();
// output
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
// Installation Engine completed all steps successfully.
//
