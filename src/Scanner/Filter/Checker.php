<?php


namespace Scanner\Filter;

use Scanner\Driver\Node;

interface Checker
{
    public function append(Checker $checker): Checker;

    public function can(Node $node): bool;
}