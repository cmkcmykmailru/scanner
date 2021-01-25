<?php

namespace Scanner\Driver\File\System\Read;

use Scanner\Driver\Component;
use Scanner\Driver\ContextSupport;
use Scanner\Driver\File\System\Read\Strategy\ReadStrategy;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class ReadSupport extends AbstractSupport implements FileRead
{

    private static $self = null;
    private ReadStrategy $readStrategy;

    protected function installMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->addMethodCallListener($this, 'read');
    }

    protected function uninstallMethods(Component $component): void
    {
        ContextSupport::getFunctionalitySupport($component)->removeMethodCallListener($this, 'read');
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