<?php


namespace ScannerTest\Driver\File;


use Scanner\Driver\ContextSupport;
use Scanner\Driver\Component;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class DummySupport extends AbstractSupport
{
    private static $self = null;

    protected function installMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'get1');
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'get2');
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'get3');
    }

    protected function uninstallMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'get1');
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'get2');
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'get3');
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new self();
            return static::$self;
        }
        return static::$self;
    }

    public function get1(Component $component, string $dummy)
    {
        return $dummy;
    }

    public function get2(Component $component)
    {
        return 'get2';
    }
    public function get3(Component $component)
    {
        return $component->getSource();
    }
}