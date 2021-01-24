<?php


namespace Scanner\Driver\File\System;

use Scanner\Driver\File\Component;
use Scanner\Event\MethodCallListener;

interface Support extends MethodCallListener
{
    public static function create(Component $component): self;

    public function install(Component $component): void;

    public function uninstall(Component $component): void;

}