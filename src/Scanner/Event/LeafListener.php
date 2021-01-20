<?php

namespace Scanner\Event;

interface LeafListener extends Listener
{

    public function leafDetected(NodeEvent $event): void;

}