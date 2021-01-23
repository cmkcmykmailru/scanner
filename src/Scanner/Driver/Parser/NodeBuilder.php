<?php


namespace Scanner\Driver\Parser;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;

interface NodeBuilder
{
    public function buildNode($detect, $found): Node;

    public function buildLeaf($detect, $found): Leaf;
}