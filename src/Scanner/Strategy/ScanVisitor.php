<?php

namespace Scanner\Strategy;

use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;

interface ScanVisitor
{
    public function detectStarted(DetectEvent $evt): void;

    public function detectCompleted(DetectEvent $evt): void;

    public function leafDetected(NodeEvent $event): void;

    public function nodeDetected(NodeEvent $event): void;
}