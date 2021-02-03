<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\NodeFactory;

interface ScanVisitor
{
    public function detectStarted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function detectCompleted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function leafDetected(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found): void;

    public function nodeDetected(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found): void;
}