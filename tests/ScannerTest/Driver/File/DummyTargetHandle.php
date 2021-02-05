<?php

namespace ScannerTest\Driver\File;

use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\TargetHandler;

class DummyTargetHandle implements TargetHandler
{
    public function handle(NodeFactory $factory, $detect, $found)
    {
        $file = $factory->createLeaf($detect, $found);
        $source = $file->getSource();
        $file->revokeAllSupports();
        return $source;
    }
}