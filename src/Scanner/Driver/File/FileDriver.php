<?php


namespace Scanner\Driver\File;

use Scanner\Driver\AbstractDriver;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Driver\Parser\Parser;

class FileDriver extends AbstractDriver
{
    protected NodeFactory $nodeFactory;
    protected PathParser $parser;

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
}