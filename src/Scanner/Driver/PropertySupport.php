<?php


namespace Scanner\Driver;


use Scanner\Event\PropertyEvent;
use Scanner\Event\PropertyListener;

class PropertySupport
{

    private ListenerStorage $storage;

    public function __construct(ListenerStorage $storage)
    {
        $this->storage = $storage;
    }

    public function addPropertyChangeListener(PropertyListener $listener, string $propertyName): void
    {
        $this->storage->add($listener, $propertyName);
    }

    public function removePropertyChangeListener(PropertyListener $listener, string $propertyName): void
    {
        $this->storage->remove($listener, $propertyName);
    }

    public function firePropertyEvent($source, string $propName, $oldProp, $newProp): void
    {
        $listeners = $this->storage->getBy($propName);
        if ($listeners === null) {
            return;
        }

        $evt = new PropertyEvent($source, $oldProp, $newProp, $propName);

        foreach ($listeners as $listener) {
            $listener->firePropertyChange($evt);
        }
    }

}