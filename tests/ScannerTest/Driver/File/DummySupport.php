<?php

namespace ScannerTest\Driver\File;

use Scanner\Driver\Component;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class DummySupport extends AbstractSupport
{
    private static $self = null;

    protected function installMethods(Component $component): void
    {
        $this->assignMethod($component, 'get1');
        $this->assignMethod($component, 'get2');
        $this->assignMethod($component, 'get3');
    }

    protected function uninstallMethods(Component $component): void
    {
        $this->revokeMethod($component, 'get1');
        $this->revokeMethod($component, 'get2');
        $this->revokeMethod($component, 'get3');
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


    protected function checkArguments($method, $arguments): bool
    {
        if ('get1' === $method) {
            return (count($arguments) === 1 && is_string($arguments[0]));
        }

        if ('get2' === $method) {
            return empty($arguments);
        }

        if ('get3' === $method) {
            return empty($arguments);
        }
        return false;
    }
}