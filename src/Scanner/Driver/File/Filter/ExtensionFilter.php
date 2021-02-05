<?php

namespace Scanner\Driver\File\Filter;

use Scanner\Exception\SearchConfigurationException;
use Scanner\Filter\Filter;

class ExtensionFilter implements Filter
{
    private $config;

    public function filter($path): bool
    {
        $pathInfo = pathinfo($path);
        if (!isset($pathInfo['extension'])) return false;
        return $this->config === $pathInfo['extension'];
    }

    public function setConfiguration($config): void
    {
        if (!is_string($config)) {
            throw new SearchConfigurationException('Invalid filter parameter type. String expected.');
        }
        $this->config = $config;
    }
}