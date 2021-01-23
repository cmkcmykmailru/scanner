<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\Parser;

class PathParser implements Parser, Explorer
{
    private $detect;

    public function parese($source): array
    {
        return array_slice(scandir($source), 2);
    }

    public function setDetect($detect): void
    {
        $this->detect = $detect . DIRECTORY_SEPARATOR;
    }

    public function isLeaf($found): bool
    {
        return !is_dir($this->detect . $found);
    }
}