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
        $this->fireStartDetected($detect);

        $queue = new SplQueue();
        $queue->enqueue($detect);

        $nodeFactory = $driver->getNodeFactory();
        $explorer = $driver->getExplorer();
        $parser = $driver->getParser();
        $cnode = null;

        while ($queue->count() > 0) {
            $cnode = $node = $queue->dequeue();
            $founds = $parser->parese($node);

            /** @var Explorer $explorer */
            $explorer->setDetect($node);

            foreach ($founds as $key => $found) {
                if ($this->stop) {
                    $this->fireCompleteDetected($node);
                    return;
                }
                if ($explorer->isLeaf($found)) {
                    if ($leafVerifier->can($explorer->next())) {
                        $this->fireLeafDetected($nodeFactory, $node, $found);
                    }
                } else {
                    if ($nodeVerifier->can($explorer->next())) {
                        $this->fireNodeDetected($nodeFactory, $node, $found);
                    }
                    $queue->enqueue($explorer->next());
                }
            }
        }
        $this->fireCompleteDetected($cnode);
    }

}