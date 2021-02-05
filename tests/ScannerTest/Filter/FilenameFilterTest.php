<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\Filter\NameWithoutExtFilter;
use Scanner\Exception\SearchConfigurationException;

class FilenameFilterTest extends TestCase
{

    public function testFilter()
    {
        $filenameFilter = new NameWithoutExtFilter();
        $filenameFilter->setConfiguration('FileName');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(true, $filenameFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileNam';

        self::assertEquals(false, $filenameFilter->filter($badNode));

        $filenameFilter->setConfiguration('FileName.php');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php.ted';

        self::assertEquals(true, $filenameFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(false, $filenameFilter->filter($badNode));

    }

    public function testSetConfiguration()
    {
        $this->expectException(SearchConfigurationException::class);
        $basenameFilter = new NameWithoutExtFilter();
        $basenameFilter->setConfiguration(['php']);
    }
}
