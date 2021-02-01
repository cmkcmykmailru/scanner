<?php

namespace Scanner\Strategy;

class SingleScanStrategy extends AbstractScanStrategy
{

    public function detect($detect, $driver, $leafVerifier, $nodeVerifier): void
    {
        if ($this->stop) {
            return;
        }
        $this->fireStartDetected($detect);

        $nodeFactory = $driver->getNodeFactory();
        $explorer = $driver->getExplorer();

        $founds = $driver->getParser()->parese($detect);
        $explorer->setDetect($detect);

        foreach ($founds as $found) {
            if ($this->stop) {
                $this->fireCompleteDetected($detect);
                return;
            }
            if ($explorer->isLeaf($found)) {
                $leafFound = $nodeFactory->createLeaf($detect, $found);

                if ($leafVerifier->can($leafFound)) {
                    $this->fireLeafDetected($leafFound);
                }
            } else {
                $nodeFound = $nodeFactory->createNode($detect, $found);

                if ($nodeVerifier->can($nodeFound)) {
                    $this->fireNodeDetected($nodeFound);
                }

            }
        }

        $this->fireCompleteDetected($detect);
    }

}