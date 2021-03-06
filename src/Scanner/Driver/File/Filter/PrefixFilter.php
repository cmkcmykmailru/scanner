<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Exception\SearchConfigurationException;
use Scanner\Filter\Filter;

class PrefixFilter implements Filter
{
    private $config;

    public function filter($path): bool
    {
        $sub = substr(basename($path), 0, mb_strlen($this->config));
        return $sub === $this->config;
    }

    public function setConfiguration($config): void
    {
        if (!is_string($config)) {
            throw new SearchConfigurationException('Invalid filter parameter type. String expected.');
        }
        $this->config = $config;
    }
}