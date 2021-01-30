<?php

namespace Scanner\Filter;

use Scanner\Driver\Node;

interface Filter
{
    public function filter(Node $node): bool;
}