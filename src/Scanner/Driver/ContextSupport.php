<?php


namespace Scanner\Driver;

use Scanner\Driver\File\Component;
use Scanner\Scanner;
use SplObjectStorage;

class ContextSupport
{

    private static ?SplObjectStorage $listenerSupports = null;
    private static ?SplObjectStorage $propertySupports = null;

    private function __construct()
    {

    }

    public static function getSupport(Scanner $driver): ListenerSupport
    {
        if (self::$listenerSupports === null) {
            self::$listenerSupports = new SplObjectStorage();
        }
        if (!self::$listenerSupports->contains($driver)) {
            self::$listenerSupports[$driver] = new ListenerSupport(new ListenerStorage());
        }

        return self::$listenerSupports[$driver];
    }

    public static function getPropertySupport(Component $component): PropertySupport
    {
        if (self::$propertySupports === null) {
            self::$propertySupports = new SplObjectStorage();
        }
        if (!self::$propertySupports->contains($component)) {
            self::$propertySupports[$component] = new PropertySupport(new ListenerStorage());
        }

        return self::$propertySupports[$component];
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }
}