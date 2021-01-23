<?php

namespace Scanner\Filter;

use Scanner\Driver\Node;

interface NodeFilter
{
    public function filterNode(Node $found): bool;
}