<?php

namespace Scanner\Driver;

use Scanner\Event\DetectEvent;
use Scanner\Event\DetectListener;
use Scanner\Event\NodeEvent;
use Scanner\Event\LeafListener;
use Scanner\Event\NodeListener;

/**
 * Class NodeListenerSupport
 * @package Scanner
 */
class ListenerSupport
{
    /**
     * @var ListenerStorage
     */
    private ListenerStorage $storage;

    /**
     *
     */
    public const TYPE_NODE = 'NODE';

    /**
     *
     */
    public const TYPE_LEAF = 'LEAF';

    /**
     *
     */
    public const TYPE_DETECTED = 'DETECTED';

    /**
     * NodeListenerSupport constructor.
     * @param $storage
     */
    public function __construct(ListenerStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param LeafListener $listener
     */
    public function addLeafListener(LeafListener $listener): void
    {
        $this->storage->add($listener, self::TYPE_LEAF);
    }

    /**
     * @param LeafListener $listener
     */
    public function removeLeafListener(LeafListener $listener): void
    {
        $this->storage->remove($listener, self::TYPE_LEAF);
    }

    /**
     * @param NodeListener $listener
     */
    public function addNodeListener(NodeListener $listener): void
    {
        $this->storage->add($listener, self::TYPE_NODE);
    }

    /**
     * @param NodeListener $listener
     */
    public function removeNodeListener(NodeListener $listener): void
    {
        $this->storage->remove($listener, self::TYPE_NODE);
    }

    /**
     * @param DetectListener $listener
     */
    public function addDetectedListener(DetectListener $listener): void
    {
        $this->storage->add($listener, self::TYPE_DETECTED);
    }

    /**
     * @param DetectListener $listener
     */
    public function removeDetectedListener(DetectListener $listener): void
    {
        $this->storage->remove($listener, self::TYPE_DETECTED);
    }

    /**
     * @param $source
     * @param $node
     */
    public function fireNodeDetected($source, Node $node): void
    {
        /* @var $listeners NodeListener[] */
        $listeners = $this->storage->getBy(self::TYPE_NODE);
        if ($listeners === null) {
            return;
        }

        $evt = new NodeEvent($source, $node, NodeEvent::NODE_DETECTED);

        foreach ($listeners as $key => $listener) {
            $listener->nodeDetected($evt);
        }
    }

    /**
     * @param $source
     * @param $leaf
     */
    public function fireLeafDetected($source, Leaf $leaf): void
    {
        /* @var $listeners LeafListener[] */
        $listeners = $this->storage->getBy(self::TYPE_LEAF);
        if ($listeners === null) {
            return;
        }

        $evt = new NodeEvent($source, $leaf, NodeEvent::LEAF_DETECTED);

        foreach ($listeners as $key => $listener) {
            $listener->leafDetected($evt);
        }
    }

    /**
     * @param $source
     * @param string $detect
     */
    public function fireStartDetected($source, string $detect): void
    {
        /* @var $listeners DetectListener[] */
        $listeners = $this->storage->getBy(self::TYPE_DETECTED);
        if ($listeners === null) {
            return;
        }

        $evt = new DetectEvent($source, $detect, DetectEvent::START_DETECTED);

        foreach ($listeners as $key => $listener) {
            $listener->detectStarted($evt);
        }
    }

    /**
     * @param $source
     * @param $detect
     */
    public function fireCompleteDetected($source, string $detect): void
    {
        /* @var $listeners DetectListener[] */
        $listeners = $this->storage->getBy(self::TYPE_DETECTED);
        if ($listeners === null) {
            return;
        }

        $evt = new DetectEvent($source, $detect, DetectEvent::END_DETECTED);

        foreach ($listeners as $key => $listener) {
            $listener->detectCompleted($evt);
        }
    }
}