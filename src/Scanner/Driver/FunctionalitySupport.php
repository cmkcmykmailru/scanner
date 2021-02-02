<?php

namespace Scanner\Driver;

use ArrayObject;
use BadMethodCallException;
use RuntimeException;
use Scanner\Event\CallMethodEvent;
use Scanner\Event\MethodCallListener;

class FunctionalitySupport
{

    private array $storage = [];

    public function installMethod(MethodCallListener $listener, string $methodName): void
    {
        if (array_key_exists($methodName, $this->storage)) {
            throw new RuntimeException('This method ' . $methodName . '() already exists.');
        }
        $this->storage[$methodName] = $listener;
    }

    public function uninstallMethod(string $methodName): void
    {
        unset($this->storage[$methodName]);
    }

    public function fireCallMethodEvent($source, string $methodName, $arguments)
    {
        if (!isset($this->storage[$methodName])) {
            throw new BadMethodCallException('Calling unknown method ' . get_class($source) . '::' . $methodName . '(...))');
        }

        $evt = new CallMethodEvent($source, $methodName, $arguments);
        $support = $this->storage[$methodName];
        return $support->methodCalled($evt);
    }

    /**
     * @param array $storage
     */
    public function setStorage(array $storage): void
    {
        $this->storage = $storage;
    }

    public function copyStorage(): array
    {
        $copy = new ArrayObject($this->storage);
        return $copy->getArrayCopy();
    }

    public function clear(): void
    {
        $this->storage = [];
    }
}