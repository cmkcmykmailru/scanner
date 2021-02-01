<?php


namespace ScannerTest\Driver;

use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;
use Scanner\Strategy\ScanVisitor;

class DummyVisitor implements  ScanVisitor
{


    public function detectStarted(DetectEvent $evt): void
    {
        // TODO: Implement detectStarted() method.
    }

    public function detectCompleted(DetectEvent $evt): void
    {
        // TODO: Implement detectCompleted() method.
    }

    public function leafDetected(NodeEvent $event): void
    {
        // TODO: Implement leafDetected() method.
    }

    public function nodeDetected(NodeEvent $event): void
    {
        // TODO: Implement nodeDetected() method.
    }
}