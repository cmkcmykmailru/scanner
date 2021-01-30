<?php

namespace Scanner\Driver\Search;

use Psr\Container\ContainerInterface;
use Scanner\Event\PropertyEvent;
use Scanner\Event\PropertyListener;
use Scanner\Scanner;

class DefaultSettingsInstaller implements SettingsInstaller, PropertyListener
{
    private ?Scanner $scanner;
    private ?ContainerInterface $container;

    /**
     * DefaultSettingsBuilder constructor.
     * @param Scanner|null $scanner
     */
    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    protected function setupFilters(SearchSettings $settings): void
    {
        $filterSettings = $settings->getFilter();

        if (empty($filterSettings)) {
            return;
        }
        $driver = $settings->getDriver();

        $this->scanner->resetLeafFilters();
        $this->scanner->resetNodeFilters();


        $leafFilters = $driver->resolveLeafFilters($filterSettings, $this->container);
        foreach ($leafFilters as $leafFilter) {
            $this->scanner->addLeafFilter($leafFilter);
        }

        $nodeFilters = $driver->resolveNodeFilters($filterSettings, $this->container);

        foreach ($nodeFilters as $nodeFilter) {
            $this->scanner->addNodeFilter($nodeFilter);
        }
    }

    protected function setupDriver(SearchSettings $settings): void
    {
        $this->scanner->setDriver($settings->getDriver());
    }

    protected function setupSupports(SearchSettings $settings): void
    {
        $supports = $settings->getSupport();

        if (empty($supports)) {
            return;
        }

        $driver = $this->scanner->getDriver();
        $driver->getNodeFactory()->needSupportsOf($supports);
    }

    public function propertyChanged(PropertyEvent $evt): void
    {
        $settings = $evt->getNewProperty();
        $this->setupFilters($settings);
        $this->setupDriver($settings);
        $this->setupSupports($settings);
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function install(Scanner $scanner): void
    {
        $this->setContainer($scanner->getContainer());
        $this->installListeners();
    }

    private function installListeners(): void
    {
        $this->scanner->addPropertyListener($this, Scanner::SEARCH);
    }

    private function uninstallListeners(): void
    {
        $this->scanner->removePropertyListener($this, Scanner::SEARCH);
    }

    public function uninstall(Scanner $scanner): void
    {
        $this->uninstallListeners();
        $this->scanner = null;
        $this->container = null;
    }
}