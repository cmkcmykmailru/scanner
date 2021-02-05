<?php

namespace Scanner\Strategy;

use Scanner\Driver\Parser\Explorer;

class SingleScanStrategy extends AbstractScanStrategy
{

    public function detect($detect, $driver, $leafVerifier, $nodeVerifier): void
    {
        if ($this->stop) {
            return;
        }
        $this->fireScanStarted($detect);

        $nodeFactory = $driver->getNodeFactory();
        /** @var Explorer $explorer */
        $explorer = $driver->getExplorer();

        $founds = $driver->getParser()->parese($detect);
        $explorer->setDetect($detect);

        foreach ($founds as $found) {
            if ($this->stop) {
                $this->fireScanCompleted($detect);
                return;
            }
            if ($explorer->isLeaf($found)) {
                if ($leafVerifier->can($explorer->whole())) {
                    $this->fireVisitLeaf($nodeFactory, $detect, $found);
                }
            } else {
                if ($nodeVerifier->can($explorer->whole())) {
                    $this->fireVisitNode($nodeFactory, $detect, $found);
                }
            }
        }

        $this->fireScanCompleted($detect);
    }

}