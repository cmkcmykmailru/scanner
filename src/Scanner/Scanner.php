<?php

namespace Scanner;

use Scanner\Driver\Driver;
use Scanner\Driver\File\FileDriver;
use Scanner\Event\DetectAdapter;
use Scanner\Event\DetectListener;
use Scanner\Event\LeafListener;
use Scanner\Event\NodeListener;

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
        $detect = $this->driver->getNormalizer()->normalise($detect);
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

    public function addDetectAdapter(DetectAdapter $adapter): void
    {
        $this->driver->addNodeListener($adapter);
        $this->driver->addLeafListener($adapter);
        $this->driver->addDetectedListener($adapter);
    }

    public function removeDetectAdapter(DetectAdapter $adapter): void
    {
        $this->driver->removeNodeListener($adapter);
        $this->driver->removeLeafListener($adapter);
        $this->driver->removeDetectedListener($adapter);
    }

    public function addNodeListener(NodeListener $listener): void
    {
        $this->driver->addNodeListener($listener);
    }

    public function addLeafListener(LeafListener $listener): void
    {
        $this->driver->addLeafListener($listener);
    }

    public function addDetectedListener(DetectListener $listener): void
    {
        $this->driver->addDetectedListener($listener);
    }

    public function removeNodeListener(NodeListener $listener): void
    {
        $this->driver->removeNodeListener($listener);
    }

    public function removeLeafListener(LeafListener $listener): void
    {
        $this->driver->removeLeafListener($listener);
    }

    public function removeDetectedListener(DetectListener $listener): void
    {
        $this->driver->removeDetectedListener($listener);
    }


}