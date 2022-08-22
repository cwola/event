<?php

declare(strict_types=1);

namespace Cwola\Event\Map;

use LogicException;

class HashMap implements \Countable {

    /**
     * @var string|null
     */
    protected string|null $head = null;

    /**
     * @var string|null
     */
    protected string|null $tail = null;

    /**
     * @var \Cwola\Event\Map\HashMapNode[]
     */
    protected array $items = [];


    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value) :static {
        if ($this->has($key)) {
            $this->getNode($key)->value = $value;
            return $this;
        }

        $node = new HashMapNode;
        $node->value = $value;
        if ($this->tail !== null) {
            $this->getNode($this->tail)->next = $key;
            $node->prev = $this->tail;
        }
        if ($this->head === null) {
            $this->head = $key;
        }
        $this->tail = $key;
        $this->items[$key] = $node;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key) :mixed {
        $node = $this->getNode($key);
        if ($node instanceof HashMapNode) {
            return $node->value;
        }
        return null;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function unset(string $key) :mixed {
        if (!$this->has($key)) {
            return null;
        }

        /** @var \Cwola\Event\Map\HashMapNode $target */
        $target = $this->getNode($key);
        if ($this->count() < 2) {
            $this->clear();
            return $target->value;
        }

        $newHead = null;
        $newTail = null;

        // prev
        if ($target->prev !== null) {
            $prev = $this->getNode($target->prev);
            $prev->next = $target->next;
            if ($target->next === null) {
                $newTail = $target->prev;
            }
        } else if ($key !== $this->head) {
            throw new LogicException('');
        }

        // next
        if ($target->next !== null) {
            $next = $this->getNode($target->next);
            $next->prev = $target->prev;
            if ($target->prev === null) {
                $newHead = $target->next;
            }
        } else if ($key !== $this->tail) {
            throw new LogicException('');
        }

        if ($newHead !== null) {
            $this->head = $newHead;
        }
        if ($newTail !== null) {
            $this->tail = $newTail;
        }
        return $target->value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key) :bool {
        return isset($this->items[$key]);
    }

    /**
     * @param void
     * @return mixed
     */
    public function head() :mixed {
        return ($this->head !== null) ? $this->get($this->head) : null;
    }

    /**
     * @param void
     * @return mixed
     */
    public function tail() :mixed {
        return ($this->tail !== null) ? $this->get($this->tail) : null;
    }

    /**
     * @param void
     * @return $this
     */
    public function clear() :static {
        $this->head = null;
        $this->tail = null;
        $this->items = [];
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int {
        return \count($this->items);
    }

    /**
     * @param callable $callback
     * @param ...$args
     * @return void
     */
    public function forEach(callable $callback, ...$args) :void {
        $key = $this->head;
        while ($key !== null) {
            $node = $this->getNode($key);
            if ($callback($key, $node->value, ...$args) === false) {
                break;
            }
            $key = $node->next;
        }
    }



    /**
     * @param string $key
     * @return \Cwola\Event\Map\HashMapNode|null
     */
    protected function getNode(string $key) :HashMapNode|null {
        return $this->has($key) ? $this->items[$key] : null;
    }
}
