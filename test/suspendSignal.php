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

$suspendController = new Event\Signal\Controller\SuspendController;
$signal = $suspendController->signal;
$signal->addEventListener('suspend', function(Event\Event $event) {
    echo \sprintf('on suspend (%s).', $event->target->reason) . PHP_EOL;
});
$signal->addEventListener('resume', function(Event\Event $event) {
    echo 'on resume.' . PHP_EOL;
});

$engine = new FooEngine;
$engine->addEventListener('beforeBoot', function(Event\Event $event) {
    echo sprintf('%s : start boot method.', $event->timeStamp) . PHP_EOL;
}, ['signal' => $signal]);
$engine->addEventListener('booted', function(Event\Event $event) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
}, ['signal' => $signal]);
$engine->addEventListener('booted', function(Event\Event $event) {
    echo 'notify...' . PHP_EOL;
}, ['signal' => $signal]);

$suspendController->suspend('test');
// output
// on suspend (test).
//

$engine->boot();
// output
//

$suspendController->resume();
// output
// on resume.
//

$engine->boot();
// output
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
//
