<?php

namespace Scanner\Driver\File\System\Read;

use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\File\System\Read\Strategy\ReadStrategy;
use Scanner\Driver\File\System\Read\Strategy\YamlReadStrategy;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class ReadSupport extends AbstractSupport implements FileRead
{

    private static $self = null;
    private ReadStrategy $readStrategy;

    /**
     * ReadSupport constructor.
     */
    public function __construct()
    {
        $this->readStrategy = new YamlReadStrategy();
    }

    protected function checkArguments($method, $arguments): bool
    {
        return empty($arguments);
    }

    protected function installMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->installMethod($this, 'read');
    }

    protected function uninstallMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->uninstallMethod( 'read');
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new self();
            return static::$self;
        }
        return static::$self;
    }

    /**
     * @return ReadStrategy
     */
    public function getReadStrategy(): ReadStrategy
    {
        return $this->readStrategy;
    }

    /**
     * @param ReadStrategy $readStrategy
     */
    public function setReadStrategy(ReadStrategy $readStrategy): void
    {
        $this->readStrategy = $readStrategy;
    }

    public function equalsStrategyName(string $name): bool
    {
        return $this->readStrategy->getName() === $name;
    }

    public function read(Component $component)
    {
        return $this->readStrategy->read($component);
    }
}