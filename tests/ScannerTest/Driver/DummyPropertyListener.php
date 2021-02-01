<?php

namespace ScannerTest\Driver;

use Scanner\Event\PropertyEvent;
use Scanner\Event\PropertyListener;

class DummyPropertyListener implements PropertyListener
{

    public function propertyChanged(PropertyEvent $evt): void
    {
        // TODO: Implement propertyChanged() method.
    }
}