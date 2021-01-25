<?php


namespace Scanner\Driver\Support;

use Scanner\Driver\Component;
use Scanner\Event\MethodCallListener;

interface Support extends MethodCallListener
{
    public static function create(Component $component): self;

    public function install(Component $component): void;

    public function uninstall(Component $component): void;

}