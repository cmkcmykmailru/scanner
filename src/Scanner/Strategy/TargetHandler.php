<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\NodeFactory;

interface TargetHandler
{
    public function handle(NodeFactory $factory, $detect, $found);
}