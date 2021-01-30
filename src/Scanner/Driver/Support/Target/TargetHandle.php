<?php

namespace Scanner\Driver\Support\Target;

use Scanner\Driver\Component;

interface TargetHandle
{
    public function handle(Component $component): void;
}