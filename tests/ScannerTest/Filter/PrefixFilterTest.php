<?php

namespace ScannerTest\Filter;

use Scanner\Driver\File\Filter\PrefixFilter;
use PHPUnit\Framework\TestCase;
use Scanner\Driver\Node;

class PrefixFilterTest extends TestCase
{

    public function testFilter()
    {
        $coolNode = $this->createMock(Node::class);
        $coolNode->method('getSource')
            ->willReturn('var' . DIRECTORY_SEPARATOR . 'prefixFileName.php');

        $prefixFilter = new PrefixFilter('prefix');
        self::assertEquals(true, $prefixFilter->filter($coolNode));


        $badNode = $this->createMock(Node::class);
        $badNode->method('getSource')
            ->willReturn('var' . DIRECTORY_SEPARATOR . 'FileName.php');

        self::assertEquals(false, $prefixFilter->filter($badNode));
    }

}
