<?php

namespace ScannerTest\Filter;

use Scanner\Driver\Node;
use Scanner\Filter\Filter;

class DummyFilter implements Filter
{
    private  $check;

    /**
     * DummyFilter constructor.
     * @param $check
     */
    public function __construct(callable $check)
    {
        $this->check = $check;
    }

    public function filter(Node $node): bool
    {
        return $this->check->__invoke($node);
    }
}