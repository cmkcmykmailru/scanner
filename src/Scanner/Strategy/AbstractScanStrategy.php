<?php

namespace Scanner\Strategy;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Scanner;

abstract class AbstractScanStrategy
{
    protected bool $stop = false;
    protected ?Scanner $scanner;

    /**
     *
     * @param Scanner $scanner
     */
    public function installScanner(Scanner $scanner): void
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
        $this->scanner->getScanVisitor()->leafDetected($this, $leaf);
    }

    protected function fireNodeDetected(Node $node): void
    {
        $this->scanner->getScanVisitor()->nodeDetected($this, $node);
    }

    protected function fireStartDetected(string $detect): void
    {
        $this->scanner->getScanVisitor()->detectStarted($this, $detect);
    }

    protected function fireCompleteDetected(string $detect): void
    {
        $this->scanner->getScanVisitor()->detectCompleted($this, $detect);
    }

}