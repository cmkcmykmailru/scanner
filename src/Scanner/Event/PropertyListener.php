<?php


namespace Scanner\Event;

interface PropertyListener extends Listener
{
    public function propertyChanged(PropertyEvent $evt): void;
}