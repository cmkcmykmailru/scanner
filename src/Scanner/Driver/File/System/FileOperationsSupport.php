<?php


namespace Scanner\Driver\File\System;

use Scanner\Driver\Component;

interface FileOperationsSupport extends Support
{
    public function callRead(Component $component);

    public function callWrite(Component $component);

    public function callRemove(Component $component);

    public function callCopy(Component $component, string $path);

    public function callMove(Component $component, string $path);

    public function callRename(Component $component, string $name);

    public function callInfo(Component $component);
}