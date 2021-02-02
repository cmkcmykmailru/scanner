<?php


namespace Scanner\Driver;

use Scanner\Driver\Support\Support;

class Component
{
    public function addSupport(Support $support): void
    {
        $support->install($this);
    }

    public function removeSupport(Support $support): void
    {
        $support->uninstall($this);
    }

    public function __call($method, $args)
    {
        return ContextSupport::getFunctionalitySupport($this)->fireCallMethodEvent($this, $method, $args);
    }

    public function removeSupports(): void
    {
        ContextSupport::removeFunctionalitySupport($this);
    }

    public function equals(Component $component): bool
    {
        return $this === $component;
    }

    public function cloneWithData($data): Component
    {
        $support = ContextSupport::getFunctionalitySupport($this);
        $copyStorage = $support->copyStorage();
        $clone = clone $this;
        $clone->setData($data);
        $cloneSupport = ContextSupport::getFunctionalitySupport($clone);
        $cloneSupport->setStorage($copyStorage);
        return $clone;
    }

    protected function setData($data): void
    {

    }
}