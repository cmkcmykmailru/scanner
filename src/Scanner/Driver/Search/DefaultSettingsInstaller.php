<?php

namespace Scanner\Driver\Search;

use Psr\Container\ContainerInterface;
use Scanner\Event\PropertyEvent;
use Scanner\Event\PropertyListener;
use Scanner\Exception\SearchConfigurationException;
use Scanner\Scanner;
use Scanner\Strategy\BreadthTraversalScanStrategy;
use Scanner\Strategy\ProxyScanVisitor;
use Scanner\Strategy\SingleScanStrategy;

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
        $driver = $this->scanner->getDriver();

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

    protected function setupStrategy(SearchSettings $settings): void
    {

        $strategyParams = $settings->getStrategy();
        if (empty($strategyParams)) {
            $this->scanner->setScanStrategy(new BreadthTraversalScanStrategy());
            return;
        }

        if (isset($strategyParams['STRATEGY'])) {
            if ($strategyParams['STRATEGY'] === 'BREADTH_TRAVERSAL') {
                $strategy = new BreadthTraversalScanStrategy();
            } else if ($strategyParams['STRATEGY'] === 'SINGLE') {
                $strategy = new SingleScanStrategy();
            } else {
                if (!$this->container->has($strategyParams['STRATEGY'])) {
                    throw new SearchConfigurationException($strategyParams['STRATEGY'] . ' - No such scan strategy defined.');
                }
                $strategy = $this->container->get($strategyParams['STRATEGY']);
            }
        } else {
            $strategy = new BreadthTraversalScanStrategy();
        }
        $this->scanner->setScanStrategy($strategy);

        if (!isset($strategyParams['handle'])) {
            $proxyVisitor = $this->scanner->getScanVisitor();
            if ($proxyVisitor instanceof ProxyScanVisitor) {
                $visitor = $proxyVisitor->extract();
                $proxyVisitor->clear();
                $this->scanner->setScanVisitor($visitor);
            }
            return;
        }

        $handlerParams = $strategyParams['handle'];

        $leafHandler = null;
        if (isset($handlerParams['leaf'])) {
            $leafParams = $handlerParams['leaf'];
            if (!isset($leafParams[0])) {
                throw new SearchConfigurationException('Invalid configuration format.');
            }
            if (!$this->container->has($leafParams[0])) {
                throw new SearchConfigurationException($leafParams[0] . ' - handler not found.');
            }
            $leafHandler = $this->container->get($leafParams[0]);
            if (isset($leafParams['multiTarget'])) {
                try {
                    $this->scanner->setLeafMultiTarget($leafParams['multiTarget']);
                } catch (\Exception $e) {
                    throw new SearchConfigurationException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }

        $nodeHandler = null;
        if (isset($handlerParams['node'])) {
            $nodeParams = $handlerParams['node'];
            if (!isset($nodeParams[0])) {
                throw new SearchConfigurationException('Invalid configuration format.');
            }
            if (!$this->container->has($nodeParams[0])) {
                throw new SearchConfigurationException($nodeParams[0] . ' - handler not found.');
            }
            $nodeHandler = $this->container->get($nodeParams[0]);
            if (isset($nodeParams['multiTarget'])) {
                try {
                    $this->scanner->setNodeMultiTarget($nodeParams['multiTarget']);
                } catch (\Exception $e) {
                    throw new SearchConfigurationException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }
        $realVisitor = $this->scanner->getScanVisitor();

        if ($realVisitor instanceof ProxyScanVisitor) {
            $realVisitor->update($this->scanner, $leafHandler, $nodeHandler);
            return;
        }
        $this->scanner->setScanVisitor(new ProxyScanVisitor($this->scanner, $realVisitor, $leafHandler, $nodeHandler));
    }

    public function propertyChanged(PropertyEvent $evt): void
    {
        if ($evt->getType() === Scanner::SEARCH) {
            $settings = $evt->getNewProperty();
            $this->setupDriver($settings);
            $this->setupFilters($settings);
            $this->setupSupports($settings);
            $this->setupStrategy($settings);
        } elseif ($evt->getType() === Scanner::SCAN_VISITOR) {
            $oldVisitor = $evt->getOldProperty();
            $newVisitor = $evt->getNewProperty();
            if ($oldVisitor instanceof ProxyScanVisitor && !$newVisitor instanceof ProxyScanVisitor) {
                $oldVisitor->clear();
            }
        }
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container): void
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
        $this->scanner->addPropertyListener($this, Scanner::SCAN_VISITOR);
    }

    private function uninstallListeners(): void
    {
        $this->scanner->removePropertyListener($this, Scanner::SEARCH);
        $this->scanner->removePropertyListener($this, Scanner::SCAN_VISITOR);
    }

    public function uninstall(Scanner $scanner): void
    {
        $this->uninstallListeners();
        $this->scanner = null;
        $this->container = null;
    }
}