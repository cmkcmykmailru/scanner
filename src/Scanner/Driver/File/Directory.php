<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Component;
use Scanner\Driver\Node;

class Directory extends Component implements Node
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->path;
    }

    protected function setData($data): void
    {
        $this->path = $data;
    }
}