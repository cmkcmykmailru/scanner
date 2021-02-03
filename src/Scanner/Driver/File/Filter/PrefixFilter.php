<?php

namespace Scanner\Driver\File\Filter;

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

    public function filter($node): bool
    {
        $sub = substr(basename($node), 0, mb_strlen($this->filterSetting));
        return $sub === $this->filterSetting;
    }
}