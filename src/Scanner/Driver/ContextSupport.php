<?php


namespace Scanner\Driver;

use Scanner\Scanner;
use SplObjectStorage;

class ContextSupport
{

    private static ?SplObjectStorage $propertySupports = null;
    private static ?SplObjectStorage $functionalitySupports = null;

    private function __construct()
    {

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

    public static function getFunctionalitySupport(Component $component): FunctionalitySupport
    {
        if (self::$functionalitySupports === null) {
            self::$functionalitySupports = new SplObjectStorage();
        }
        if (!self::$functionalitySupports->contains($component)) {
            self::$functionalitySupports[$component] = new FunctionalitySupport();
        }

        return self::$functionalitySupports[$component];
    }

    public static function removeFunctionalitySupport(Component $component): void
    {
        if (self::$functionalitySupports === null) {
            return;
        }
        if (!self::$functionalitySupports->contains($component)) {
            return;
        }
        self::$functionalitySupports[$component]->clear();
        self::$functionalitySupports->detach($component);
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }
}