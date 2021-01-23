<?php


namespace Scanner\Driver\File;


use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Driver\Parser\NodeFactory;

class PathNodeFactory implements NodeFactory
{

    public function createNode($detect, $found): Node
    {
        return new Directory($detect . DIRECTORY_SEPARATOR . $found);
    }

    public function createLeaf($detect, $found): Leaf
    {
        return new File($detect . DIRECTORY_SEPARATOR . $found);
    }
}