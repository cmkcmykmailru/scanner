<?php


namespace Scanner\Driver;

use SplObjectStorage;

class ContextSupport
{

    private static ?SplObjectStorage $listenerSupports = null;


    private function __construct()
    {

    }

    public static function getSupport(Driver $driver): ListenerSupport
    {
        if (self::$listenerSupports === null) {
            self::$listenerSupports = new SplObjectStorage();
        }
        if (!self::$listenerSupports->contains($driver)) {
            self::$listenerSupports[$driver] = new ListenerSupport(new ListenerStorage());
        }

        return self::$listenerSupports[$driver];
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }
}