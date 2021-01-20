<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Leaf;

class File implements Leaf
{
    private string $path;

    /**
     * FileNode constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getSource(): string
    {
        return $this->path;
    }
}