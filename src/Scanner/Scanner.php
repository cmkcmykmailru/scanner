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
use Scanner\Filter\Filter;
use Scanner\Filter\Verifier;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\BreadthTraversalScanStrategy;
use Scanner\Strategy\ScanVisitor;

/**
 * Class Scanner
 * @package Scanner
 */
class Scanner extends Component
{
    public const SEARCH = 'SEARCH';
    public const STOP = 'STOP';
    public const SET_DRIVER = 'SET_DRIVER';
    public const TRAVERSAL_VISITOR = 'TRAVERSAL_VISITOR';
    /**
     * @var Driver
     */
    private ?Driver $driver = null;
    private ?Verifier $leafVerifier = null;
    private ?Verifier $nodeVerifier = null;
    private bool $stop = false;
    protected ContainerInterface $container;
    protected ?SearchSettings $searchSettings = null;
    protected SettingsInstaller $settingsInstaller;
    protected ?ScanVisitor $visitor = null;
    protected AbstractScanStrategy $traversal;

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
        $this->traversal = $this->getScanStrategy();
    }

    protected function createContainer(array $config = []): ContainerInterface
    {
        return new ServiceManager($config);
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

    public function search(SearchSettings $settings)
    {
        $oldValue = $this->searchSettings;
        $this->searchSettings = $settings;
        $this->firePropertyChange(self::SEARCH, $oldValue, $settings);
        $detect = $this->searchSettings->getSearchCriteria()['source'];

        $this->detect($detect);
    }

    /**
     * @param $detect
     */
    public function detect($detect): void
    {
        if ($this->visitor === null) {
            throw new \RuntimeException('TraversalVisitor is not installed.');
        }
        $detect = $this->driver->getNormalizer()->normalise($detect);
        $this->traversal->detect($detect, $this->driver, $this->leafVerifier, $this->nodeVerifier);
    }

    protected function getScanStrategy(): AbstractScanStrategy
    {
        return new BreadthTraversalScanStrategy($this);
    }

    public function setScanVisitor(ScanVisitor $visitor): void
    {
        $oldValue = $this->visitor;
        $this->visitor = $visitor;
        $this->firePropertyChange(self::TRAVERSAL_VISITOR, $oldValue, $visitor);
    }

    public function getScanVisitor(): ?ScanVisitor
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

}