<?php

namespace Scanner\Strategy;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;
use Scanner\Scanner;

abstract class AbstractScanStrategy
{
    protected bool $stop = false;
    protected Scanner $scanner;

    /**
     * DefaultTraversal constructor.
     * @param Scanner $scanner
     */
    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    abstract public function detect($detect, $driver, $leafVerifier, $nodeVerifier): void;

    /**
     * @return bool
     */
    public function isStop(): bool
    {
        return $this->stop;
    }

    /**
     * @param bool $stop
     */
    public function setStop(bool $stop): void
    {
        $this->stop = $stop;
    }

    protected function fireLeafDetected(Leaf $leaf): void
    {
        $evt = new NodeEvent($this, $leaf, NodeEvent::LEAF_DETECTED);
        $this->scanner->getScanVisitor()->leafDetected($evt);
    }

    protected function fireNodeDetected(Node $node): void
    {
        $evt = new NodeEvent($this, $node, NodeEvent::NODE_DETECTED);
        $this->scanner->getScanVisitor()->nodeDetected($evt);
    }

    protected function fireStartDetected(string $detect): void
    {
        $evt = new DetectEvent($this, $detect, DetectEvent::START_DETECTED);
        $this->scanner->getScanVisitor()->detectStarted($evt);
    }

    protected function fireCompleteDetected(string $detect): void
    {
        $evt = new DetectEvent($this, $detect, DetectEvent::END_DETECTED);
        $this->scanner->getScanVisitor()->detectCompleted($evt);
    }

}