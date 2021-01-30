<?php


namespace Scanner\Filter;


use Scanner\Driver\Node;

class BaseChecker extends AbstractChecker
{
    private Filter $filter;

    /**
     * BaseChecker constructor.
     * @param Filter $filter
     */
    public function __construct(?Filter $filter = null)
    {
        if ($filter !== null) {
            $this->filter = $filter;
        }
    }

    public function can(Node $node): bool
    {
        return $this->filter->filter($node) ? parent::can($node) : false;
    }

}