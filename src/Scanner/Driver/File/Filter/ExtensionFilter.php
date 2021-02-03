<?php

namespace Scanner\Driver\File\Filter;

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

    public function filter($node): bool
    {
        $pathInfo = pathinfo($node);
        if (!isset($pathInfo['extension'])) return false;
        return $this->filterSetting === $pathInfo['extension'];
    }
}