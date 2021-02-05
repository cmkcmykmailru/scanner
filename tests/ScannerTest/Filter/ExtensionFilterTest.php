<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\Filter\ExtensionFilter;
use Scanner\Exception\SearchConfigurationException;

class ExtensionFilterTest extends TestCase
{

    public function testFilter()
    {
        $extensionFilter = new ExtensionFilter();
        $extensionFilter->setConfiguration('php');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(true, $extensionFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.csv';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.h';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'h.';

        self::assertEquals(false, $extensionFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.phpg';

        self::assertEquals(false, $extensionFilter->filter($badNode));
    }

    public function testSetConfiguration()
    {
        $this->expectException(SearchConfigurationException::class);
        $extensionFilter = new ExtensionFilter();
        $extensionFilter->setConfiguration(['php']);
    }
}
