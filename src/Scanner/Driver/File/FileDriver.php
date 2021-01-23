<?php


namespace Scanner\Driver\File;

use Scanner\Driver\AbstractDriver;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeBuilder;
use Scanner\Driver\Parser\Parser;

class FileDriver extends AbstractDriver
{
    protected PathNodeBuilder $filesNodeBuilder;
    protected PathParser $pathParser;

    /**
     * FileDriver constructor.
     */
    public function __construct()
    {
        $this->setNormalizer(new PathNormalizer());
        $this->filesNodeBuilder = new PathNodeBuilder();
        $this->pathParser = new PathParser();
    }

    protected function getParser(): Parser
    {
        return $this->pathParser;
    }

    protected function getExplorer(): Explorer
    {
        return $this->pathParser;
    }

    protected function getNodeBuilder(): NodeBuilder
    {
        return $this->filesNodeBuilder;
    }
}