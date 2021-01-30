<?php

namespace ScannerTest\Driver\File;

use Scanner\Driver\Component;
use Scanner\Driver\Support\Target\TargetHandle;

class DummyTargetHandle implements TargetHandle
{

    public function handle(Component $component): void
    {
        echo $component->getSource();
    }
}