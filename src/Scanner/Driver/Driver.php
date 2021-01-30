<?php

namespace Scanner\Driver;

use Psr\Container\ContainerInterface;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Driver\Parser\Parser;

interface Driver
{

    public function getExplorer(): Explorer;

    public function getParser(): Parser;

    public function getNodeFactory(): NodeFactory;

    public function getNormalizer(): Normalizer;

    public function resolveLeafFilters(array $filterSettings, ContainerInterface $container): \Generator;

    public function resolveNodeFilters(array $filterSettings, ContainerInterface $container): \Generator;
}