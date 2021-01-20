<?php

namespace Scanner\Event;

abstract class AbstractEvent implements Event
{

    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

}
