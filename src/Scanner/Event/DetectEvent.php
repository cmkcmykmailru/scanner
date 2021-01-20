<?php


namespace Scanner\Event;

class DetectEvent extends AbstractEvent
{
    private string $detect;
    private string $type;
    public const START_DETECTED = 'START_DETECTED';
    public const END_DETECTED = 'END_DETECTED';

    public function __construct($source, string $detect, string $type)
    {
        $this->detect = $detect;
        $this->type = $type;
        parent::__construct($source);
    }

    /**
     * @return string
     */
    public function getDetect(): string
    {
        return $this->detect;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}