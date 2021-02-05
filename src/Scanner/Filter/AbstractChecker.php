<?php

namespace Scanner\Filter;

abstract class AbstractChecker implements Checker
{
    protected ?Checker $next = null;

    public function append(Checker $checker): Checker
    {
        $this->next = $checker;
        return $checker;
    }

    public function can($node): bool
    {
        if ($this->next !== null) {
            return $this->next->can($node);
        }
        return true;
    }
}