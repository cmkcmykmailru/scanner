<?php

namespace Scanner\Driver\File\System\Read\Strategy;

use Scanner\Driver\Component;

class YamlReadStrategy implements ReadStrategy
{

    public function read(Component $component)
    {
        return \yaml_parse_file($component->getSource());
    }

    public function getName(): string
    {
        return 'YAML';
    }
}