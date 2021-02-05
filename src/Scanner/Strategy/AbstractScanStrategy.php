<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\NodeFactory;
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

    public function uninstallScanner(): void
    {
        $this->scanner = null;
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

    protected function fireVisitLeaf(NodeFactory $factory, $detect, $found): void
    {
        $this->scanner->getScanVisitor()->visitLeaf($this, $factory, $detect, $found);
    }

    protected function fireVisitNode(NodeFactory $factory, $detect, $found): void
    {
        $this->scanner->getScanVisitor()->visitNode($this, $factory, $detect, $found);
    }

    protected function fireScanStarted(string $detect): void
    {
        $this->scanner->getScanVisitor()->scanStarted($this, $detect);
    }

    protected function fireScanCompleted(string $detect): void
    {
        $this->scanner->getScanVisitor()->scanCompleted($this, $detect);
    }

}