<?php

namespace Scanner\Driver\Support\Target;

use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class TargetSupport extends AbstractSupport
{
    private static ?TargetSupport $self = null;
    private TargetHandle $handle;

    protected function checkArguments($method, $arguments): bool
    {
        return empty($arguments);
    }

    protected function installMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'target');
    }

    protected function uninstallMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'target');
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new static();
            return static::$self;
        }
        return static::$self;
    }

    /**
     * @return TargetHandle
     */
    public function getHandle(): TargetHandle
    {
        return $this->handle;
    }

    /**
     * @param TargetHandle $handle
     */
    public function setHandle(TargetHandle $handle): void
    {
        $this->handle = $handle;
    }

    public function target(Component $component)
    {
        $this->handle->handle($component);
    }

}