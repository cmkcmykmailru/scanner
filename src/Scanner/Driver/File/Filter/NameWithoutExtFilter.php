<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Exception\SearchConfigurationException;
use Scanner\Filter\Filter;

class NameWithoutExtFilter implements Filter
{
    private $config;

    public function filter($path): bool
    {
        $name = pathinfo($path, PATHINFO_FILENAME);
        return $this->config === $name;
    }

    public function setConfiguration($config): void
    {
        if (!is_string($config)) {
            throw new SearchConfigurationException('Invalid filter parameter type. String expected.');
        }
        $this->config = $config;
    }
}