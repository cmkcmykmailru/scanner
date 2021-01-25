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
}