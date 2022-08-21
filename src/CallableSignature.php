<?php

declare(strict_types=1);

namespace Cwola\Event;

use Closure;
use Exception;
use Cwola\Attribute\Readable;

/**
 * @property string $type [readonly]
 * @property string $signature [readonly]
 */
class CallableSignature {

    use Readable;

    /**
     * @var string
     */
    const SIGNATURE_ANONYMOUS = 'anonymous';

    /**
     * @var string
     */
    const SIGNATURE_STRING = 'string';

    /**
     * @var string
     */
    const SIGNATURE_ARRAY = 'array';

    /**
     * @var string
     */
    const SIGNATURE_INSTANCE = 'instance';


    /**
     * @var string
     */
    #[Readable]
    protected string $type;

    /**
     * @var string
     */
    #[Readable]
    protected string $signature;


    /**
     * @param callable $callback
     *
     * @throws \Exception
     */
    public function __construct(callable $callback) {
        if (\is_object($callback)) {
            if ($callback instanceof Closure) {
                $this->type = static::SIGNATURE_ANONYMOUS;
            } else {
                $this->type = static::SIGNATURE_INSTANCE;
            }
        } else if (\is_string($callback)) {
            $this->type = static::SIGNATURE_STRING;
        } else if (\is_array($callback)) {
            $this->type = static::SIGNATURE_ARRAY;
        }
        $this->signature = $this->genSignature($callback);
    }

    /**
     * @param mixed $sig
     * @return string
     *
     * @throws \Exception
     */
    protected function genSignature(mixed $sig) :string {
        if (\is_object($sig)) {
            return \spl_object_hash($sig) . '#' . \spl_object_id($sig);
        } else if (\is_string($sig)) {
            return $sig;
        } else if (\is_array($sig)) {
            return $this->genSignature($sig[0]) . '::' . $this->genSignature($sig[1]);
        }
        throw new Exception('Unexpected arguments.');
    }
}
