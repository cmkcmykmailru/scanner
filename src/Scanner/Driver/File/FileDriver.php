<?php


namespace Scanner\Driver\File;

use Scanner\Driver\Driver;
use Scanner\Driver\Normalizer;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Driver\Parser\Parser;

class FileDriver implements Driver
{
    protected NodeFactory $nodeFactory;
    protected PathParser $parser;
    protected Normalizer $normalizer;

    /**
     * FileDriver constructor.
     * @param NodeFactory $factory
     */
    public function __construct(NodeFactory $factory)
    {
        $this->setNormalizer(new PathNormalizer());
        $this->nodeFactory = $factory;
        $this->parser = new PathParser();
    }

    public function getParser(): Parser
    {
        return $this->parser;
    }

    public function getExplorer(): Explorer
    {
        return $this->parser;
    }

    public function getNodeFactory(): NodeFactory
    {
        return $this->nodeFactory;
    }

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
}