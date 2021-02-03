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
                if ($leafVerifier->can($explorer->next())) {
                    $this->fireLeafDetected($nodeFactory, $detect, $found);
                }
            } else {
                if ($nodeVerifier->can($explorer->next())) {
                    $this->fireNodeDetected($nodeFactory, $detect, $found);
                }
            }
        }

        $this->fireCompleteDetected($detect);
    }

}