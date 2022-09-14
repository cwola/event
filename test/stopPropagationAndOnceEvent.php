<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

use Cwola\Event;

class FooEngine implements Event\EventTarget {
    use Event\EventDispatcher;

    public function __construct() {
        $this->addEventListener('initialize', function(Event\Event $event) {
            $this->init();
            // equals $event->target->init();
        }, ['once' => true]);
    }

    public function boot() {
        $this->dispatchEvent('initialize');

        $this->dispatchEvent('beforeBoot');

        echo 'boot process... wait 10 sec...' . PHP_EOL;
        sleep(10);

        $this->dispatchEvent('booted');
    }

    protected function init() {
        echo 'init...' . PHP_EOL;
    }
}

$engine = new FooEngine;

$engine->addEventListener('beforeBoot', function(Event\Event $event) {
    echo sprintf('%s : start boot method.', $event->timeStamp) . PHP_EOL;
});
$engine->addEventListener('booted', function(Event\Event $event) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
    $event->stopPropagation();
});
$engine->addEventListener('booted', function(Event\Event $event) {
    // not reached.
    echo 'not reached' . PHP_EOL;
});

$engine->boot();
// output
// init...
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
//

$engine->boot();
// output
// 2022-08-22T23:33:01+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:11+09:00 : end boot method.
//