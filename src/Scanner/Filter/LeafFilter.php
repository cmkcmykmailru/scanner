<?php

namespace Scanner\Filter;

use Scanner\Driver\Leaf;

interface LeafFilter
{
    public function filterLeaf(Leaf $found): bool;
}