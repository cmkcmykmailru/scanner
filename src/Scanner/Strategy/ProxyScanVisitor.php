<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\NodeFactory;
use Scanner\Scanner;

class ProxyScanVisitor implements ScanVisitor
{
    protected ?TargetHandler $leafHandler = null;
    protected ?TargetHandler $nodeHandler = null;
    protected ?ScanVisitor $realVisitor = null;
    protected ?Scanner $scanner = null;

    /**
     * ProxyScanVisitor constructor.
     * @param TargetHandler|null $leafHandler
     * @param TargetHandler|null $nodeHandler
     * @param ScanVisitor $realVisitor
     * @param Scanner $scanner
     */
    public function __construct(
        Scanner $scanner,
        ScanVisitor $realVisitor,
        ?TargetHandler $leafHandler,
        ?TargetHandler $nodeHandler
    )
    {
        $this->leafHandler = $leafHandler;
        $this->nodeHandler = $nodeHandler;
        $this->realVisitor = $realVisitor;
        $this->scanner = $scanner;
    }

    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->realVisitor->scanStarted($scanStrategy, $detect);
    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->realVisitor->scanCompleted($scanStrategy, $detect);
    }

    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        if ($this->leafHandler === null) {
            $this->realVisitor->visitLeaf($scanStrategy, $factory, $detect, $found, $data);
            return;
        }
        $result = $this->leafHandler->handle($factory, $detect, $found);
        if ($result !== null) {
            $this->realVisitor->visitLeaf($scanStrategy, $factory, $detect, $found, $result);
            $scanStrategy->setStop(!$this->scanner->isLeafMultiTarget());
        }
    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        if ($this->nodeHandler === null) {
            $this->realVisitor->visitNode($scanStrategy, $factory, $detect, $found, $data);
            return;
        }
        $result = $this->nodeHandler->handle($factory, $detect, $found);
        if ($result !== null) {
            $this->realVisitor->visitNode($scanStrategy, $factory, $detect, $found, $result);
            $scanStrategy->setStop(!$this->scanner->isNodeMultiTarget());
        }
    }

    public function clear()
    {
        $this->leafHandler = null;
        $this->nodeHandler = null;
        $this->realVisitor = null;
        $this->scanner = null;
    }

    public function update(
        Scanner $scanner,
        ?TargetHandler $leafHandler,
        ?TargetHandler $nodeHandler
    ): void
    {
        $this->leafHandler = $leafHandler;
        $this->nodeHandler = $nodeHandler;
        $this->scanner = $scanner;
    }

    public function extract(): ScanVisitor
    {
        return $this->realVisitor;
    }

    public function __destruct()
    {
        $this->clear();
    }
}