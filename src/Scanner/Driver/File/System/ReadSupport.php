<?php


namespace Scanner\Driver\File\System;


use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class ReadSupport extends AbstractSupport implements FileRead
{

    private static $self = null;

    protected function installMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'read');
    }

    protected function uninstallMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'read');
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new self();
            return static::$self;
        }
        return static::$self;
    }


    public function read(Component $component)
    {

    }
}