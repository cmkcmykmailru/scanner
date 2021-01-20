<?php

namespace Scanner;

use Scanner\Driver\Driver;
use Scanner\Driver\File\FileDriver;

/**
 * Class Scanner
 * @package Scanner
 */
class Scanner
{
    /**
     * @var Driver
     */
    private Driver $driver;

    /**
     * Scanner constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver = null)
    {
        if ($driver === null) {
            $this->driver = new FileDriver();
        } else {
            $this->driver = $driver;
        }
    }

    /**
     * @param $detect
     */
    public function detect($detect): void
    {
        $this->driver->detect($detect);
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     */
    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

}