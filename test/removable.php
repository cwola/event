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

$engine = new FooEngine;
$engine->addEventListener('beforeBoot', function(Event\Event $event) {
    echo sprintf('%s : start boot method.', $event->timeStamp) . PHP_EOL;
});
$bootEnd = function(Event\Event $event) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
};
$removableListener = function(Event\Event $event) {
    echo 'not reached' . PHP_EOL;
};
$engine->addEventListener('booted', $bootEnd, ['removable' => false]);
$engine->addEventListener('booted', $removableListener);
$engine->removeEventListener('booted', $bootEnd);
$engine->removeEventListener('booted', $removableListener);

$engine->boot();
// output
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
//