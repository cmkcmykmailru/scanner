<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Driver\Parser\NodeFactory;

class FileFactory implements NodeFactory
{
    private array $directorySupports = [];
    private array $fileSupports = [];

    public function createNode($detect, $found): Node
    {
        $directory = new Directory($detect . DIRECTORY_SEPARATOR . $found);

        foreach ($this->directorySupports as $support) {
            $directory->addSupport($support::create($directory));
        }

        return $directory;
    }

    public function createLeaf($detect, $found): Leaf
    {
        $file = new File($detect . DIRECTORY_SEPARATOR . $found);

        foreach ($this->fileSupports as $support) {
            $file->addSupport($support::create($file));
        }

        return $file;
    }

    public function needSupportsOf(array $supports): void
    {
        if (isset($supports['FILE'])) {
            $this->fileSupports = $supports['FILE'];
        }
        if (isset($supports['DIRECTORY'])) {
            $this->directorySupports = $supports['DIRECTORY'];
        }
    }
}