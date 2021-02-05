<?php

namespace Scanner\Driver\File\System\Read;

use Scanner\Driver\Component;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;

class YamlReadSupport extends AbstractSupport
{

    private static $self = null;

    protected function installMethods(Component $component): void
    {
        $this->assignMethod($component, 'yamlParseFile');
    }

    protected function uninstallMethods(Component $component): void
    {
        $this->revokeMethod($component, 'yamlParseFile');
    }

    protected function checkArguments($method, $arguments): bool
    {
        return empty($arguments);
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new self();
            return static::$self;
        }
        return static::$self;
    }

    public function yamlParseFile(Component $component)
    {
        return \yaml_parse_file($component->getSource());
    }
}