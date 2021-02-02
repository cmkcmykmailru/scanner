<?php

namespace Scanner\Strategy;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;

interface ScanVisitor
{
    public function detectStarted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function detectCompleted(AbstractScanStrategy $scanStrategy, $detect): void;

    public function leafDetected(AbstractScanStrategy $scanStrategy, Leaf $leaf): void;

    public function nodeDetected(AbstractScanStrategy $scanStrategy, Node $node): void;
}