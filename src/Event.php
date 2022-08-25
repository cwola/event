<?php

declare(strict_types=1);

namespace Cwola\Event;

use DateTime;
use Cwola\Attribute\Readable;

/**
 * @property string $id [readonly]
 * @property string $timeStamp [readonly]
 * @property string $type [readonly]
 * @property \Cwola\Event\EventTarget $target [readonly]
 * @property bool $cancelable [readonly]
 * @property bool $propagationStoped [readonly]
 * @property bool $defaultPrevented [readonly]
 */
class Event {

    use Readable;

    /**
     * @var string
     */
    #[Readable]
    protected string $id;

    /**
     * @var string
     */
    #[Readable]
    protected string $timeStamp;

    /**
     * @var string
     */
    #[Readable]
    protected string $type;

    /**
     * @var \Cwola\Event\EventTarget
     */
    #[Readable]
    protected EventTarget $target;

    /**
     * @var bool
     */
    #[Readable]
    protected bool $cancelable = true;

    /**
     * @var bool
     */
    #[Readable]
    protected bool $defaultPrevented = false;

    /**
     * @var bool
     */
    #[Readable]
    protected bool $propagationStoped = false;


    /**
     * @param string $type
     * @param \Cwola\Event\EventOptions $options
     * @param \Cwola\Event\EventTarget $target
     */
    public function __construct(string $type, EventTarget $target) {
        $this->id = \md5(\uniqid($type, true));
        $this->timeStamp = (new DateTime('now'))->format('c');
        $this->type = $type;
        $this->target = $target;
    }

    /**
     * @param void
     * @return void
     */
    public function preventDefault() :void {
        if ($this->cancelable) {
            $this->defaultPrevented = true;
        }
    }

    /**
     * @param void
     * @return void
     */
    public function stopPropagation() :void {
        $this->propagationStoped = true;
    }

    /**
     * @param void
     * @return void
     */
    public function handleDefault() :void {
        // do something.
    }
}
