# Event

Simple event listener.

## Overview

Simple event listener.

## Requirement
- PHP8.0+
- [cwola/attribute](https://packagist.org/packages/cwola/attribute)

## Installation
```
composer require cwola/event
```

## Usage of EventTarget
```
<?php
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
$engine->addEventListener('booted', function(Event\Event $event) {
    echo sprintf('%s : end boot method.', $event->timeStamp) . PHP_EOL;
});
$engine->boot();
// output
// 2022-08-22T23:32:51+09:00 : start boot method.
// boot process... wait 10 sec...
// 2022-08-22T23:33:01+09:00 : end boot method.
//

```

## Stop propagation / OnceEvent
```
<?php

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
    echo 'not reached';
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

```

## AbortSignal
```
<?php

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

$abortController = new Event\AbortController;
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
    if (isNight()) {
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

```

## Licence

[MIT](https://github.com/cwola/event/blob/main/LICENSE)
