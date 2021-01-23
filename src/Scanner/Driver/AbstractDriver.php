<?php

namespace Scanner\Driver;


abstract class AbstractDriver implements Driver
{
    protected $normalizer;

    public function setNormalizer(Normalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return Normalizer
     */
    public function getNormalizer(): Normalizer
    {
        return $this->normalizer;
    }

}