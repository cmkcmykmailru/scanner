<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Driver\Node;
use Scanner\Filter\Filter;

class ExtensionFilter implements Filter
{
    private $filterSetting;

    /**
     * ExtensionFilter constructor.
     * @param $filterSetting
     */
    public function __construct($filterSetting)
    {
        $this->filterSetting = $filterSetting;
    }

    public function filter(Node $node): bool
    {
        $pathInfo = pathinfo($node->getSource());
        if (!isset($pathInfo['extension'])) return false;
        return $this->filterSetting === $pathInfo['extension'];
    }
}