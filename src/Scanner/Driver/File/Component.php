<?php


namespace Scanner\Driver\File;

use Scanner\Driver\ContextSupport;
use Scanner\Driver\File\System\Support;

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
        return ContextSupport::getFunctionalitySupport($this)->fireCallEvent($this, $method, $args);
    }
}