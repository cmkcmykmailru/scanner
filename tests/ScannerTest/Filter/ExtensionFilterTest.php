<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\Filter\ExtensionFilter;
use Scanner\Driver\Node;

class ExtensionFilterTest extends TestCase
{

    public function testFilter()
    {
        $coolNode = $this->createMock(Node::class);
        $coolNode->method('getSource')
            ->willReturn('var' . DIRECTORY_SEPARATOR . 'FileName.php');

        $extensionFilter = new ExtensionFilter('php');
        self::assertEquals(true, $extensionFilter->filter($coolNode));


        $badNode = $this->createMock(Node::class);
        $badNode->method('getSource')
            ->willReturn('var' . DIRECTORY_SEPARATOR . 'FileName.csv');

        self::assertEquals(false, $extensionFilter->filter($badNode));
    }
}
