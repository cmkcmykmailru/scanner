<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Exception\SearchConfigurationException;
use Scanner\Filter\Filter;

class BasenameFilter implements Filter
{
    private $config;

    public function filter($path): bool
    {
        return $this->config === basename($path);
    }

    public function setConfiguration($config): void
    {
        if (!is_string($config)) {
            throw new SearchConfigurationException('Invalid filter parameter type. String expected.');
        }
        $this->config = $config;
    }
}