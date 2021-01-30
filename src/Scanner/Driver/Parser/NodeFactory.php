<?php


namespace Scanner\Driver\Parser;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;

interface NodeFactory
{
    public function needSupportsOf(array $supports): void;

    public function createNode($detect, $found): Node;

    public function createLeaf($detect, $found): Leaf;
}