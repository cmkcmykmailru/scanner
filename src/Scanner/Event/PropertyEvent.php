<?php


namespace Scanner\Event;


class PropertyEvent extends AbstractEvent
{
    private $oldProperty;
    private $newProperty;
    private string $type;

    public function __construct($source, $oldProperty, $newProperty, string $type)
    {
        $this->newProperty = $newProperty;
        $this->oldProperty = $oldProperty;
        $this->type = $type;
        parent::__construct($source);
    }

    /**
     * @return mixed
     */
    public function getOldProperty()
    {
        return $this->oldProperty;
    }

    /**
     * @return mixed
     */
    public function getNewProperty()
    {
        return $this->newProperty;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}