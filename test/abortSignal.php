<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

use Cwola\Event;

class FooEngine implements Event\EventTarget {
    use Event\EventDispatcher;

    public function boot() {
        $this->dispatchEvent('beforeBoot');

        echo 'boot process... wait 10 sec...' . PHP_EOL;
        sleep(10);

        $this->dispatchEvent('booted');
    }
}

$abortController = new Event\Signal\Controller\AbortController;
$signal = $abortController->signal;
$signal->addEventListener('abort', function(Event\Event $event) {
    echo 'No notification during the night.' . PHP_EOL;
});

$engine = new FooEngine;
$engine->addEventListener('beforeBoot', function(Event\Event $event) {
    echo sprintf('%s : start boot method.', $event->timeStamp) . PHP_EOL;
});
$engine->addEventListener('booted', function(Event\Event $event) use ($abortController) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
    // No notification during the night.
    if (true) {
        $abortController->abort();
    }
}); 
$engine->addEventListener('booted', function(Event\Event $event) {
    echo 'notify...' . PHP_EOL;
}, ['signal' => $signal]);


$engine->boot();
// output
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
// No notification during the night.
//
