<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\NodeFactory;

interface ScanVisitor
{
    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;
}