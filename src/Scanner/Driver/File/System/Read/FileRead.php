<?php

namespace Scanner\Driver\File\System\Read;

use Scanner\Driver\Component;

interface FileRead
{
    public function read(Component $component);
}