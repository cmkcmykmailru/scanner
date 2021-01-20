<?php

namespace Scanner\Event;

use Scanner\Driver\Node;

class NodeEvent extends AbstractEvent
{
    private Node $node;
    private string $type;
    public const NODE_DETECTED = 'NODE_DETECTED';
    public const LEAF_DETECTED = 'LEAF_DETECTED';

    /**
     * NodeEvent constructor.
     * @param $source
     * @param Node $node
     * @param string $type
     */
    public function __construct($source, Node $node, string $type)
    {
        $this->node = $node;
        $this->type = $type;
        parent::__construct($source);
    }

    /**
     * @return Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}