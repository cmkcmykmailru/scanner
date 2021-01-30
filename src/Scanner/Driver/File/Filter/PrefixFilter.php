<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Driver\Node;
use Scanner\Filter\Filter;

class PrefixFilter implements Filter
{
    private $filterSetting;

    /**
     * PrefixFilter constructor.
     * @param $filterSetting
     */
    public function __construct($filterSetting)
    {
        $this->filterSetting = $filterSetting;
    }

    public function filter(Node $node): bool
    {
        $sub = substr(basename($node->getSource()), 0, mb_strlen($this->filterSetting));
        return $sub === $this->filterSetting;
    }
}