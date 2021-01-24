<?php

namespace Scanner\Driver\File\System;

use Scanner\Driver\File\Component;
use Scanner\Event\CallMethodEvent;

abstract class AbstractSupport implements Support
{

    public function methodCalled(CallMethodEvent $evt)
    {
        $args = $evt->getArguments();
        $source = $evt->getSource();
        array_unshift($args, $source);

        $call = [$this, $evt->getMethod()];
        return \call_user_func_array($call, $args);
    }

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