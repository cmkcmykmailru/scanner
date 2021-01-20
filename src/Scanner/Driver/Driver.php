<?php

namespace Scanner\Driver;

use Scanner\Event\DetectListener;
use Scanner\Event\LeafListener;
use Scanner\Event\NodeListener;

interface Driver
{
    public function detect(string $node): void;

    public function addNodeListener(NodeListener $listener): void;

    public function addLeafListener(LeafListener $listener): void;

    public function addDetectedListener(DetectListener $listener): void;

    public function removeNodeListener(NodeListener $listener): void;

    public function removeLeafListener(LeafListener $listener): void;

    public function removeDetectedListener(DetectListener $listener): void;

}