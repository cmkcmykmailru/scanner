<?php

namespace Scanner\Event;

interface NodeListener extends Listener
{

    public function nodeDetected(NodeEvent $event): void;

}