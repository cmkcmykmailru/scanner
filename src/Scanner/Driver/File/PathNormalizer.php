<?php


namespace Scanner\Driver\File;

use Scanner\Driver\Normalizer;

class PathNormalizer implements Normalizer
{

    public function normalise($source)
    {
        return rtrim($source, DIRECTORY_SEPARATOR);
    }
}