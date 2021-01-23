<?php


namespace Scanner\Driver\File\System;

use Scanner\Driver\File\Component;
use Scanner\Driver\Node;

interface Support
{
    public static function create(Node $node): self;

    public function install(Component $component): void;

    public function uninstall(Component $component): void;

}