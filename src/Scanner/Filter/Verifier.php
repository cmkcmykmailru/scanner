<?php

namespace Scanner\Filter;

class Verifier
{
    private ?Checker $initialChecker;
    private ?Checker $checker;

    public function append(Filter $filter): Verifier
    {
        if (empty($this->initialChecker)) {
            $this->initialChecker = new BaseChecker($filter);
            $this->checker = $this->initialChecker;
        }
        $this->checker = $this->checker->append(new BaseChecker($filter));
        return $this;
    }

    public function can($found): bool
    {
        if (empty($this->initialChecker)) {
            return true;
        }
        return $this->initialChecker->can($found);
    }

    public function clear()
    {
        $this->initialChecker = null;
        $this->checker = null;
    }
}