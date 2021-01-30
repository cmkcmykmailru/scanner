<?php

namespace Scanner;

use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\Driver;
use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Driver\Search\DefaultSettingsInstaller;
use Scanner\Driver\Search\SearchSettings;
use Scanner\Driver\Search\SettingsInstaller;
use Scanner\Event\DetectAdapter;
use Scanner\Event\DetectListener;
use Scanner\Event\LeafListener;
use Scanner\Event\NodeListener;
use Scanner\Event\PropertyListener;
use Scanner\Filter\Filter;
use Scanner\Filter\Verifier;

/**
 * Class Scanner
 * @package Scanner
 */
class Scanner extends Component
{
    public const SEARCH = 'SEARCH';
    public const STOP = 'STOP';
    public const SET_DRIVER = 'SET_DRIVER';
    /**
     * @var Driver
     */
    private ?Driver $driver = null;
    private ?Verifier $leafVerifier = null;
    private ?Verifier $nodeVerifier = null;
    private bool $stop = false;
    protected ContainerInterface $container;
    protected ?SearchSettings $searchSettings = null;
    protected SettingsInstaller $settingsBuilder;

    /**
     * Scanner constructor.
     * @param array $config
     * @param ContainerInterface|null $container
     */
    public function __construct(array $config = [], ?ContainerInterface $container = null)
    {
        if ($container === null) {
            $this->container = $this->createContainer($config);
        } else {
            $this->container = $container;
        }

        $this->leafVerifier = new Verifier();
        $this->nodeVerifier = new Verifier();

        $this->setSettingsBuilder(new DefaultSettingsInstaller($this));
    }

    protected function createContainer(array $config = []): ContainerInterface
    {
        return new ServiceManager($config);
    }

    /**
     * @return SettingsInstaller
     */
    public function getSettingsBuilder(): SettingsInstaller
    {
        return $this->settingsBuilder;
    }

    /**
     * @param SettingsInstaller $settingsBuilder
     */
    public function setSettingsBuilder(SettingsInstaller $settingsBuilder): void
    {
        if (!empty($this->settingsBuilder)) {
            $this->settingsBuilder->uninstall($this);
        }
        $this->settingsBuilder = $settingsBuilder;
        $this->settingsBuilder->install($this);
    }

    public function search(SearchSettings $settings)
    {
        $oldValue = $this->searchSettings;
        $this->searchSettings = $settings;
        $this->firePropertyChange(self::SEARCH, $oldValue, $settings);
        $detect = $this->searchSettings->getSearchCriteria()['source'];
        $detect = $this->driver->getNormalizer()->normalise($detect);
        $this->detect($detect);
    }

    /**
     * @param $detect
     */
    public function detect($detect): void
    {
        if ($this->stop) {
            return;
        }

        $this->fireStartDetected($detect);

        $founds = $this->driver->getParser()->parese($detect);

        $nodeFactory = $this->driver->getNodeFactory();
        $explorer = $this->driver->getExplorer();
        $explorer->setDetect($detect);

        $nodes = [];
        foreach ($founds as $found) {
            if ($explorer->isLeaf($found)) {

                $leafFound = $nodeFactory->createLeaf($detect, $found);

                if ($this->leafVerifier->can($leafFound)) {
                    $this->fireLeafDetected($leafFound);
                }
            } else {

                $nodeFound = $nodeFactory->createNode($detect, $found);

                if ($this->nodeVerifier->can($nodeFound)) {
                    $nodes[] = $nodeFound;
                }
            }
            if ($this->stop) {
                $this->fireCompleteDetected($detect);
                return;
            }
        }

        foreach ($nodes as $node) {
            $this->fireNodeDetected($node);
            if ($this->stop) {
                $this->fireCompleteDetected($detect);
                return;
            }
        }

        $this->fireCompleteDetected($detect);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function resetLeafFilters(): void
    {
        $this->leafVerifier->clear();
    }

    public function resetNodeFilters(): void
    {
        $this->nodeVerifier->clear();
    }

    public function addLeafFilter(Filter $filter): void
    {
        $this->leafVerifier->append($filter);
    }

    public function addNodeFilter(Filter $filter): void
    {
        $this->nodeVerifier->append($filter);
    }

    /**
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     */
    public function setDriver(Driver $driver): void
    {
        $oldValue = $this->driver;
        $this->driver = $driver;
        $this->firePropertyChange(self::SET_DRIVER, $oldValue, $driver);
    }

    /**
     * @param bool $stop
     */
    public function stop(bool $stop): void
    {
        if ($stop === $this->stop) {
            return;
        }
        $oldValue = $this->stop;
        $this->stop = $stop;
        $this->firePropertyChange(self::STOP, $oldValue, $stop);
    }

    /**
     * @return bool
     */
    public function isStop(): bool
    {
        return $this->stop;
    }

    public function addPropertyListener(PropertyListener $listener, string $propertyName): void
    {
        ContextSupport::getPropertySupport($this)->addPropertyChangeListener($listener, $propertyName);
    }

    public function removePropertyListener(PropertyListener $listener, string $propertyName): void
    {
        ContextSupport::getPropertySupport($this)->removePropertyChangeListener($listener, $propertyName);
    }

    protected function firePropertyChange($propertyName, $oldValue, $newValue): void
    {
        ContextSupport::getPropertySupport($this)->firePropertyEvent($this, $propertyName, $oldValue, $newValue);
    }

    public function addDetectAdapter(DetectAdapter $adapter): void
    {
        $this->addNodeListener($adapter);
        $this->addLeafListener($adapter);
        $this->addDetectedListener($adapter);
    }

    public function addNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->addNodeListener($listener);
    }

    public function addLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->addLeafListener($listener);
    }

    public function addDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->addDetectedListener($listener);
    }

    public function removeNodeListener(NodeListener $listener): void
    {
        ContextSupport::getSupport($this)->removeNodeListener($listener);
    }

    public function removeLeafListener(LeafListener $listener): void
    {
        ContextSupport::getSupport($this)->removeLeafListener($listener);
    }

    public function removeDetectedListener(DetectListener $listener): void
    {
        ContextSupport::getSupport($this)->removeDetectedListener($listener);
    }

    protected function fireLeafDetected(Leaf $leaf): void
    {
        ContextSupport::getSupport($this)->fireLeafDetected($this, $leaf);
    }

    protected function fireNodeDetected(Node $node): void
    {
        ContextSupport::getSupport($this)->fireNodeDetected($this, $node);
    }

    protected function fireStartDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireStartDetected($this, $detect);
    }

    protected function fireCompleteDetected(string $detect): void
    {
        ContextSupport::getSupport($this)->fireCompleteDetected($this, $detect);
    }

}