<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\Filter\BasenameFilter;
use Scanner\Exception\SearchConfigurationException;

class BasenameFilterTest extends TestCase
{

    public function testFilter()
    {
        $basenameFilter = new BasenameFilter();
        $basenameFilter->setConfiguration('FileName.php');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.php';

        self::assertEquals(true, $basenameFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.csv';

        self::assertEquals(false, $basenameFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName';

        self::assertEquals(false, $basenameFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'FileName.';

        self::assertEquals(false, $basenameFilter->filter($badNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'php.FileName';

        self::assertEquals(false, $basenameFilter->filter($badNode));

        $basenameFilter->setConfiguration('directory');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'directory';
        self::assertEquals(true, $basenameFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . '.directory';

        self::assertEquals(false, $basenameFilter->filter($badNode));


        $badNode = 'directory' . DIRECTORY_SEPARATOR . 'var';

        self::assertEquals(false, $basenameFilter->filter($badNode));


        $basenameFilter->setConfiguration('file.php.ztp');
        $coolNode = 'var' . DIRECTORY_SEPARATOR . 'file.php.ztp';
        self::assertEquals(true, $basenameFilter->filter($coolNode));

        $badNode = 'var' . DIRECTORY_SEPARATOR . 'file.php';

        self::assertEquals(false, $basenameFilter->filter($badNode));
    }

    public function testSetConfiguration()
    {
        $this->expectException(SearchConfigurationException::class);
        $basenameFilter = new BasenameFilter();
        $basenameFilter->setConfiguration(['php']);
    }
}
