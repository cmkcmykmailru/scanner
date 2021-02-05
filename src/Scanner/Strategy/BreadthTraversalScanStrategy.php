<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\Explorer;
use SplQueue;

class BreadthTraversalScanStrategy extends AbstractScanStrategy
{

    public function detect($detect, $driver, $leafVerifier, $nodeVerifier): void
    {
        if ($this->stop) {
            return;
        }
        $this->fireScanStarted($detect);

        $queue = new SplQueue();
        $queue->enqueue($detect);

        $nodeFactory = $driver->getNodeFactory();
        $explorer = $driver->getExplorer();
        $parser = $driver->getParser();
        $completeFound = null;

        while ($queue->count() > 0) {
            $completeFound = $node = $queue->dequeue();
            $founds = $parser->parese($node);

            /** @var Explorer $explorer */
            $explorer->setDetect($node);

            foreach ($founds as $key => $found) {
                if ($this->stop) {
                    $this->fireScanCompleted($node);
                    return;
                }
                if ($explorer->isLeaf($found)) {
                    if ($leafVerifier->can($explorer->whole())) {
                        $this->fireVisitLeaf($nodeFactory, $node, $found);
                    }
                } else {
                    if ($nodeVerifier->can($explorer->whole())) {
                        $this->fireVisitNode($nodeFactory, $node, $found);
                    }
                    $queue->enqueue($explorer->whole());
                }
            }
        }
        $this->fireScanCompleted($completeFound);
    }

}