<?php

namespace Scanner\Driver;

use BadMethodCallException;
use RuntimeException;
use Scanner\Event\CallMethodEvent;
use Scanner\Event\MethodCallListener;

class FunctionalitySupport
{

    private array $storage = [];

    public function addMethodCallListener(MethodCallListener $listener, string $methodName): void
    {
        if (array_key_exists($methodName, $this->storage)) {
            throw new RuntimeException('Too many method listeners of - ' . $methodName);
        }

        $this->storage[$methodName] = $listener;
    }

    public function removeMethodCallListener(string $methodName): void
    {
        unset($this->storage[$methodName]);
    }

    public function fireCallMethodEvent($source, string $methodName, $arguments)
    {
        if (!isset($this->storage[$methodName])) {
            throw new BadMethodCallException('Calling unknown method ' . get_class($source) . '::' . $methodName . '(...))');
        }

        $evt = new CallMethodEvent($source, $methodName, $arguments);
        $listener = $this->storage[$methodName];
        return $listener->methodCalled($evt);
    }

    public function clear(): void
    {
        $storage = [];
    }
}