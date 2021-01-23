<?php


namespace Scanner\Event;

interface PropertyListener extends Listener
{
    public function firePropertyChange(PropertyEvent $evt): void;
}