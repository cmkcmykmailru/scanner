<?php

namespace Scanner\Driver\File;

use Psr\Container\ContainerInterface;
use Scanner\Driver\Driver;
use Scanner\Driver\File\Filter\ExtensionFilter;
use Scanner\Driver\File\Filter\NameWithoutExtFilter;
use Scanner\Driver\File\Filter\BasenameFilter;
use Scanner\Driver\File\Filter\PrefixFilter;
use Scanner\Driver\Normalizer;
use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Driver\Parser\Parser;
use Scanner\Exception\SearchConfigurationException;

class FileDriver implements Driver
{
    protected NodeFactory $nodeFactory;
    protected PathParser $parser;
    protected Normalizer $normalizer;

    /**
     * FileDriver constructor.
     * @param NodeFactory $factory
     */
    public function __construct(NodeFactory $factory)
    {
        $this->setNormalizer(new PathNormalizer());
        $this->nodeFactory = $factory;
        $this->parser = new PathParser();
    }

    public function getParser(): Parser
    {
        return $this->parser;
    }

    public function getExplorer(): Explorer
    {
        return $this->parser;
    }

    public function getNodeFactory(): NodeFactory
    {
        return $this->nodeFactory;
    }

    public function setNormalizer(Normalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return Normalizer
     */
    public function getNormalizer(): Normalizer
    {
        return $this->normalizer;
    }

    /**
     * @param array $filterSettings
     * @param ContainerInterface $container
     * @return \Generator
     */
    public function resolveLeafFilters(array $filterSettings, ContainerInterface $container): \Generator
    {
        if (isset($filterSettings['FILE'])) {

            foreach ($filterSettings['FILE'] as $key => $filterSetting) {

                if ($key === 'extension') {
                    $filter = new ExtensionFilter();
                } elseif ($key === 'prefix') {
                    $filter = new PrefixFilter();
                } elseif ($key === 'basename') {
                    $filter = new BasenameFilter();
                }elseif ($key === 'name_without_ext') {
                    $filter = new NameWithoutExtFilter();
                } else {
                    if (!$container->has($key)) {
                        throw new SearchConfigurationException('Filter class not found.');
                    }
                    $filter = $container->get($key);
                }
                $filter->setConfiguration($filterSetting);
                yield $filter;
            }
        }
    }

    public function resolveNodeFilters(array $filterSettings, ContainerInterface $container): \Generator
    {
        if (isset($filterSettings['DIRECTORY'])) {
            foreach ($filterSettings['DIRECTORY'] as $key => $filterSetting) {
                if ($key === 'prefix') {
                    $filter = new PrefixFilter();
                } elseif ($key === 'basename') {
                    $filter = new BasenameFilter();
                } else {
                    if (!$container->has($key)) {
                        throw new SearchConfigurationException('Filter class not found.');
                    }
                    $filter = $container->get($key);
                }
                $filter->setConfiguration($filterSetting);
                yield $filter;
            }
        }
    }

}