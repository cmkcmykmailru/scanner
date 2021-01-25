<?php

namespace ScannerTest\Driver\File;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;

class ComponentTest extends TestCase
{

    public function testAddSupport()
    {
        $file = new File('/var/www/scaner/src/Scanner/Event/LeafListener.php');
        $file->addSupport(DummySupport::create($file));
        self::assertEquals('dummy', $file->get1('dummy'));
        self::assertEquals('get2', $file->get2());
        self::assertEquals('/var/www/scaner/src/Scanner/Event/LeafListener.php', $file->get3());
    }

    public function testExeptionCallSupportMethod()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new File('/var/www/scaner/src/Scanner/Event/LeafListener.php');
        $file->addSupport(DummySupport::create($file));
        $file->get100('dummy');
    }
}
