<?php

namespace Scanner\Driver\File;

use Scanner\Driver\Leaf;
use Scanner\Driver\Node;
use Scanner\Driver\Parser\NodeFactory;

class FileFactory implements NodeFactory
{
    private File $filePrototype;
    private Directory $directoryPrototype;

    /**
     * FileFactory constructor.
     */
    public function __construct()
    {
        $this->filePrototype = new File('');
        $this->directoryPrototype = new Directory('');
    }

    public function createNode($detect, $found): Node
    {
        return $this->directoryPrototype->cloneWithData($detect . DIRECTORY_SEPARATOR . $found);
    }

    public function createLeaf($detect, $found): Leaf
    {
        return $this->filePrototype->cloneWithData($detect . DIRECTORY_SEPARATOR . $found);
    }

    public function needSupportsOf(array $supports): void
    {
        if (isset($supports['FILE'])) {
            $fileSupports = $supports['FILE'];
            $this->filePrototype->revokeAllSupports();
            foreach ($fileSupports as $key => $support) {
                $this->filePrototype->assignSupport($support::create($this->filePrototype));
            }
        }
        if (isset($supports['DIRECTORY'])) {
            $directorySupports = $supports['DIRECTORY'];
            $this->directoryPrototype->revokeAllSupports();
            foreach ($directorySupports as $key => $support) {
                $this->directoryPrototype->assignSupport($support::create($this->directoryPrototype));
            }
        }
    }

    public function __destruct()
    {
        $this->filePrototype->revokeAllSupports();
        $this->directoryPrototype->revokeAllSupports();
    }
}