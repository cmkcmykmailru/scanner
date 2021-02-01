<?php

namespace Scanner\Strategy;

use SplQueue;

class BreadthTraversalScanStrategy extends AbstractScanStrategy
{

    public function detect($detect, $driver, $leafVerifier, $nodeVerifier): void
    {
        if ($this->stop) {
            return;
        }
        $this->fireStartDetected($detect);

        $queue = new SplQueue();
        $queue->enqueue($detect);

        $nodeFactory = $driver->getNodeFactory();
        $explorer = $driver->getExplorer();
        $cnode = null;

        while ($queue->count() > 0) {
            $cnode = $node = $queue->dequeue();
            $founds = $driver->getParser()->parese($node);
            $explorer->setDetect($node);

            foreach ($founds as $found) {
                if ($this->stop) {
                    $this->fireCompleteDetected($node);
                    return;
                }
                if ($explorer->isLeaf($found)) {
                    $leafFound = $nodeFactory->createLeaf($node, $found);

                    if ($leafVerifier->can($leafFound)) {
                        $this->fireLeafDetected($leafFound);
                    }
                } else {
                    $nodeFound = $nodeFactory->createNode($node, $found);

                    if ($nodeVerifier->can($nodeFound)) {
                        $this->fireNodeDetected($nodeFound);
                    }
                    $queue->enqueue($nodeFound->getSource());
                }
            }
        }
        $this->fireCompleteDetected($cnode);
    }

}