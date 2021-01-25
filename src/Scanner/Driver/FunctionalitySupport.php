<?php

namespace Scanner\Driver;

use BadMethodCallException;
use RuntimeException;
use Scanner\Event\CallMethodEvent;
use Scanner\Event\MethodCallListener;

class FunctionalitySupport
{

    private ListenerStorage $storage;

    public function __construct(ListenerStorage $storage)
    {
        $this->storage = $storage;
    }

    public function addMethodCallListener(MethodCallListener $listener, string $methodName): void
    {
        $listeners = $this->storage->getBy($methodName);
        if ($listeners !== null) {
            throw new RuntimeException('Too many method listeners of - ' . $methodName);
        }
        $this->storage->add($listener, $methodName);
    }

    public function removeMethodCallListener(MethodCallListener $listener, string $methodName): void
    {
        $this->storage->remove($listener, $methodName);
    }

    public function fireCallMethodEvent($source, string $methodName, $arguments)
    {
        $listeners = $this->storage->getBy($methodName);
        if ($listeners === null) {
            throw new BadMethodCallException('Calling unknown method ' . get_class($source) . '::' . $methodName . '(...))');
        }
        if (count($listeners) > 1) {
            throw new RuntimeException('Too many method listeners.');
        }
        $evt = new CallMethodEvent($source, $methodName, $arguments);

        return $listeners[0]->methodCalled($evt);
    }

}