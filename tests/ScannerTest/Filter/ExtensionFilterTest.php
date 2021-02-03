<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\Filter\ExtensionFilter;

class ExtensionFilterTest extends TestCase
{

    public function testFilter()
    {
        $extensionFilter = new ExtensionFilter('php');

        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(true, $extensionFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.csv';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.h';

        self::assertEquals(false, $extensionFilter->filter($badNode));
    }
}
