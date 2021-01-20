<?php


namespace Scanner\Event;

interface DetectListener extends Listener
{
    public function detectStarted(DetectEvent $evt): void;

    public function detectCompleted(DetectEvent $evt): void;
}