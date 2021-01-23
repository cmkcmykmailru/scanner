<?php

namespace Scanner;

use Scanner\Driver\ContextSupport;
use Scanner\Driver\Driver;
use Scanner\Driver\File\FileDriver;
use Scanner\Driver\File\PathNodeFactory;
use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
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
            $this->driver = new FileDriver(new PathNodeFactory());
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

        $this->fireStartDetected($detect);

        $founds = $this->driver->getParser()->parese($detect);

        $nodeFactory = $this->driver->getNodeFactory();
        $explorer = $this->driver->getExplorer();
        $explorer->setDetect($detect);

        $nodes = [];
        foreach ($founds as $found) {
            if ($explorer->isLeaf($found)) {
                $this->fireLeafDetected($nodeFactory->createLeaf($detect, $found));
            } else {
                $nodes[] = $nodeFactory->createNode($detect, $found);
            }
        }

        foreach ($nodes as $node) {
            $this->fireNodeDetected($node);
        }

        $this->fireCompleteDetected($detect);
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
        $this->addNodeListener($adapter);
        $this->addLeafListener($adapter);
        $this->addDetectedListener($adapter);
    }

    public function addNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->addNodeListener($listener);
    }

    public function addLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->addLeafListener($listener);
    }

    public function addDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->addDetectedListener($listener);
    }

    public function removeNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->removeNodeListener($listener);
    }

    public function removeLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->removeLeafListener($listener);
    }

    public function removeDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->removeDetectedListener($listener);
    }

    protected function fireLeafDetected(Leaf $leaf): void
    {
        ContextSupport::getSupport($this)->fireLeafDetected($this, $leaf);
    }

    protected function fireNodeDetected(Node $node): void
    {
        ContextSupport::getSupport($this)->fireNodeDetected($this, $node);
    }

    protected function fireStartDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireStartDetected($this, $detect);
    }

    protected function fireCompleteDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireCompleteDetected($this, $detect);
    }

}