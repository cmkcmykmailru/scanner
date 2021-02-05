<?php

namespace Scanner;

use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\Driver;
use Scanner\Driver\Search\DefaultSettingsInstaller;
use Scanner\Driver\Search\SearchSettings;
use Scanner\Driver\Search\SettingsInstaller;
use Scanner\Event\PropertyListener;
use Scanner\Exception\SearchConfigurationException;
use Scanner\Filter\Filter;
use Scanner\Filter\Verifier;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\ProxyScanVisitor;
use Scanner\Strategy\ScanVisitor;

/**
 * Class Scanner
 * @package Scanner
 */
class Scanner extends Component
{
    public const SEARCH = 'SEARCH';
    public const SET_DRIVER = 'SET_DRIVER';
    public const SCAN_VISITOR = 'SCAN_VISITOR';
    public const PROXY_SCAN_VISITOR = 'PROXY_SCAN_VISITOR';
    public const LEAF_MULTI_TARGET = 'LEAF_MULTI_TARGET';
    public const NODE_MULTI_TARGET = 'NODE_MULTI_TARGET';
    public const SCAN_STRATEGY = 'SCAN_STRATEGY';
    /**
     * @var Driver|null
     */
    private ?Driver $driver = null;
    private Verifier $leafVerifier;
    private Verifier $nodeVerifier;
    protected ContainerInterface $container;
    protected ?SearchSettings $searchSettings = null;
    protected SettingsInstaller $settingsInstaller;
    protected ?ScanVisitor $visitor = null;
    protected ?AbstractScanStrategy $traversal = null;
    protected bool $leafMultiTarget = true;
    protected bool $nodeMultiTarget = true;

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

        $this->setSettingsInstaller(new DefaultSettingsInstaller($this));
    }

    protected function createContainer(array $config = []): ContainerInterface
    {
        return new ServiceManager($config);
    }

    /**
     * @return bool
     */
    public function isLeafMultiTarget(): bool
    {
        return $this->leafMultiTarget;
    }

    /**
     * @param bool $leafMultiTarget
     */
    public function setLeafMultiTarget(bool $leafMultiTarget): void
    {
        if ($this->leafMultiTarget === $leafMultiTarget) {
            return;
        }
        $oldValue = $this->leafMultiTarget;
        $this->leafMultiTarget = $leafMultiTarget;
        $this->firePropertyChange(self::LEAF_MULTI_TARGET, $oldValue, $leafMultiTarget);
    }

    /**
     * @return bool
     */
    public function isNodeMultiTarget(): bool
    {
        return $this->nodeMultiTarget;
    }

    /**
     * @param bool $nodeMultiTarget
     */
    public function setNodeMultiTarget(bool $nodeMultiTarget): void
    {
        if ($this->nodeMultiTarget === $nodeMultiTarget) {
            return;
        }
        $oldValue = $this->nodeMultiTarget;
        $this->nodeMultiTarget = $nodeMultiTarget;
        $this->firePropertyChange(self::NODE_MULTI_TARGET, $oldValue, $nodeMultiTarget);
    }

    /**
     * @return SettingsInstaller
     */
    public function getSettingsInstaller(): SettingsInstaller
    {
        return $this->settingsInstaller;
    }

    /**
     * @param SettingsInstaller $settingsInstaller
     */
    public function setSettingsInstaller(SettingsInstaller $settingsInstaller): void
    {
        if (!empty($this->settingsInstaller)) {
            $this->settingsInstaller->uninstall($this);
        }
        $this->settingsInstaller = $settingsInstaller;
        $this->settingsInstaller->install($this);
    }

    public function search(SearchSettings $settings): void
    {
        $oldValue = $this->searchSettings;
        $this->searchSettings = $settings;
        $this->firePropertyChange(self::SEARCH, $oldValue, $settings);
        $criteria = $this->searchSettings->getSearchCriteria();

        if (!isset($criteria['source'])) {
            throw new SearchConfigurationException('Parameter "source" is not found.');
        }

        $this->detect($criteria['source']);
    }

    /**
     * @param $detect
     */
    protected function detect($detect): void
    {
        if ($this->visitor === null && $this->container->has(ScanVisitor::class)) {
            $visitor = $this->container->get(ScanVisitor::class);
            $this->setScanVisitor($visitor);
        } else if ($this->visitor === null) {
            throw new \RuntimeException('ScanVisitor is not installed.');
        }
        $detect = $this->driver->getNormalizer()->normalise($detect);
        $this->traversal->detect($detect, $this->driver, $this->leafVerifier, $this->nodeVerifier);
    }

    public function setScanStrategy(AbstractScanStrategy $strategy): void
    {
        if (!empty($this->traversal)) {
            $this->traversal->uninstallScanner();
        }
        $oldValue = $this->traversal;
        $strategy->installScanner($this);
        $this->traversal = $strategy;
        $this->firePropertyChange(self::SCAN_STRATEGY, $oldValue, $strategy);
    }

    public function getScanStrategy(): AbstractScanStrategy
    {
        return $this->traversal;
    }

    public function setScanVisitor(ScanVisitor $visitor): void
    {
        $oldValue = $this->visitor;
        $this->visitor = $visitor;
        if ($visitor instanceof ProxyScanVisitor) {
            $this->firePropertyChange(self::PROXY_SCAN_VISITOR, $oldValue, $visitor);
            return;
        }
        $this->firePropertyChange(self::SCAN_VISITOR, $oldValue, $visitor);
    }

    public function getScanVisitor(): ScanVisitor
    {
        return $this->visitor;
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
        if ($stop === $this->traversal->isStop()) {
            return;
        }
        $this->traversal->setStop($stop);
    }

    /**
     * @return bool
     */
    public function isStop(): bool
    {
        return $this->traversal->isStop();
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

}
