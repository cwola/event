<?php

declare(strict_types=1);

namespace Cwola\Event\Map;

class HashMapNode {

    /**
     * @var string|null
     */
    public string|null $prev = null;

    /**
     * @var string|null
     */
    public string|null $next = null;

    /**
     * @var mixed
     */
    public mixed $value = null;
}
