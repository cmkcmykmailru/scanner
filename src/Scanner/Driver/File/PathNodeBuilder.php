<?php


namespace Scanner\Driver\File;


use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Driver\Parser\NodeBuilder;

class PathNodeBuilder implements NodeBuilder
{

    public function buildNode($detect, $found): Node
    {
        return new Directory($detect . DIRECTORY_SEPARATOR . $found);
    }

    public function buildLeaf($detect, $found): Leaf
    {
        return new File($detect . DIRECTORY_SEPARATOR . $found);
    }
}