<?php

namespace Scanner\Driver;

use Scanner\Driver\File\Directory;
use Scanner\Driver\File\File;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeBuilder;
use Scanner\Driver\Parser\Parser;
use Scanner\Event\DetectListener;
use Scanner\Event\LeafListener;
use Scanner\Event\NodeListener;

abstract class AbstractDriver implements Driver
{
    protected $normalizer;

    public function detect($detect): void
    {
        $this->fireStartDetected($detect);

        $founds = $this->getParser()->parese($detect);
        $nodeBuilder = $this->getNodeBuilder();
        $explorer = $this->getExplorer();
        $explorer->setDetect($detect);

        foreach ($founds as $found) {
            if ($explorer->isLeaf($found)) {
                $this->fireLeafDetected($nodeBuilder->buildLeaf($detect, $found));
            } else {
                $this->fireNodeDetected($nodeBuilder->buildNode($detect, $found));
            }
        }

        $this->fireCompleteDetected($detect);
    }

    abstract protected function getExplorer(): Explorer;

    abstract protected function getParser(): Parser;

    abstract protected function getNodeBuilder(): NodeBuilder;

    public function setNormalizer(Normalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return Normalizer
     */
    public function getNormalizer(): Normalizer
    {
        return $this->normalizer;
    }

    public function addNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->addNodeListener($listener);
    }

    public function addLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->addLeafListener($listener);
    }

    public function addDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->addDetectedListener($listener);
    }

    public function removeNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->removeNodeListener($listener);
    }

    public function removeLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->removeLeafListener($listener);
    }

    public function removeDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->removeDetectedListener($listener);
    }

    protected function fireLeafDetected(Leaf $leaf): void
    {
        ContextSupport::getSupport($this)->fireLeafDetected($this, $leaf);
    }

    protected function fireNodeDetected(Node $node): void
    {
        ContextSupport::getSupport($this)->fireNodeDetected($this, $node);
    }

    protected function fireStartDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireStartDetected($this, $detect);
    }

    protected function fireCompleteDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireCompleteDetected($this, $detect);
    }

}