<?php

namespace ScannerTest\Filter;

use Scanner\Driver\File\Filter\PrefixFilter;
use PHPUnit\Framework\TestCase;
use Scanner\Driver\Node;
use Scanner\Exception\SearchConfigurationException;

class PrefixFilterTest extends TestCase
{

    public function testFilter()
    {
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'prefixFileName.php';

        $prefixFilter = new PrefixFilter();
        $prefixFilter->setConfiguration('prefix');
        self::assertEquals(true, $prefixFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(false, $prefixFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.php';

        self::assertEquals(false, $prefixFilter->filter($badNode));

        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'prefixFileName';
        self::assertEquals(true, $prefixFilter->filter($coolNode));

        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'prefix';
        self::assertEquals(true, $prefixFilter->filter($coolNode));

        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'prefix.';
        self::assertEquals(true, $prefixFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.prefix';

        self::assertEquals(false, $prefixFilter->filter($badNode));
    }

    public function testSetConfiguration()
    {
        $this->expectException(SearchConfigurationException::class);
        $extensionFilter = new PrefixFilter();
        $extensionFilter->setConfiguration(['php']);
    }
}
