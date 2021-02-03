<?php

namespace ScannerTest\Filter;

use Scanner\Driver\File\Filter\PrefixFilter;
use PHPUnit\Framework\TestCase;
use Scanner\Driver\Node;

class PrefixFilterTest extends TestCase
{

    public function testFilter()
    {
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'prefixFileName.php';

        $prefixFilter = new PrefixFilter('prefix');
        self::assertEquals(true, $prefixFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(false, $prefixFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.php';

        self::assertEquals(false, $prefixFilter->filter($badNode));
    }

}
