<?php


namespace Scanner\Driver\File\System;

use Scanner\Driver\File\Component;

interface FileOperationsSupport extends Support
{
    public function read(Component $component);

    public function write(Component $component);

    public function remove(Component $component): void;

    public function copy(Component $component, string $path): void;

    public function move(Component $component, string $path): void;

    public function rename(Component $component, string $name): void;

    public function info(Component $component);
}