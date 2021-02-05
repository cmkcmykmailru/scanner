<?php


namespace ScannerTest\Driver;

use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\ScanVisitor;

class DummyVisitor implements  ScanVisitor
{


    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        // TODO: Implement scanStarted() method.
    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        // TODO: Implement scanCompleted() method.
    }

    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        // TODO: Implement visitLeaf() method.
    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        // TODO: Implement visitNode() method.
    }
}