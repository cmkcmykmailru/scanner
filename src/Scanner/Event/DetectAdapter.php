<?php


namespace Scanner\Event;


class DetectAdapter implements DetectListener, LeafListener, NodeListener
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