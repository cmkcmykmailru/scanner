<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Parser\Explorer;
use Scanner\Driver\Parser\Parser;

class PathParser implements Parser, Explorer
{
    private $detect;
    private $whole;

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
        $this->whole = $this->detect . $found;
        return !is_dir($this->whole);
    }

    public function whole()
    {
        return $this->whole;
    }
}