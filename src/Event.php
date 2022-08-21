<?php

declare(strict_types=1);

namespace Cwola\Event;

use DateTime;
use Cwola\Attribute\Readable;

/**
 * @property string $id [readonly]
 * @property \DateTime $timeStamp [readonly]
 * @property string $type [readonly]
 * @property \Cwola\Event\EventTarget $target [readonly]
 * @property bool $cancelable [readonly]
 * @property bool $propagationStoped [readonly]
 */
class Event {

    use Readable;

    /**
     * @var string
     */
    #[Readable]
    protected string $id;

    /**
     * @var \DateTime
     */
    #[Readable]
    protected DateTime $timeStamp;

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
    protected bool $cancelable = false;

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
        $this->timeStamp = new DateTime('now');
        $this->type = $type;
        $this->target = $target;
    }

    /**
     * @param void
     * @return void
     */
    public function stopPropagation() :void {
        if ($this->cancelable) {
            $this->propagationStoped = true;
        }
    }
}
