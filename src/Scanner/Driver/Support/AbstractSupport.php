<?php

namespace Scanner\Driver\Support;

use Scanner\Driver\Component;
use Scanner\Event\CallMethodEvent;
use Scanner\Exception\NotSupportedArgumentException;

abstract class AbstractSupport implements Support
{

    public function methodCalled(CallMethodEvent $evt)
    {
        $args = $evt->getArguments();
        $method = $evt->getMethod();

        if (!$this->checkArguments($method, $args)) {
            throw new NotSupportedArgumentException('Such arguments are not supported by the system.');
        }

        $source = $evt->getSource();
        array_unshift($args, $source);

        $call = [$this, $method];
        return \call_user_func_array($call, $args);
    }

    abstract protected function checkArguments($method, $arguments): bool;

    abstract protected function installMethods(Component $component): void;

    public function install(Component $component): void
    {
        $this->installMethods($component);
    }

    abstract protected function uninstallMethods(Component $component): void;

    public function uninstall(Component $component): void
    {
        $this->uninstallMethods($component);
    }
}