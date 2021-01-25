<?php

namespace ScannerTest\Driver\File\System\Read;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Driver\File\System\Read\ReadSupport;
use Scanner\Driver\File\System\Read\Strategy\YamlReadStrategy;

class ReadSupportTest extends TestCase
{


    public function testRead()
    {
        $file = new File(__DIR__ . '/test.yml');
        $support = ReadSupport::create($file);
        $support->setReadStrategy(new YamlReadStrategy());
        $yml = $support->read($file);
        self::assertIsArray($yml);
        self::assertArrayHasKey('version', $yml);
        self::assertEquals('3.2', $yml['version']);
    }

    public function testCreateReadSupport()
    {
        $file = new File(__DIR__ . '/test.yml');
        $support = ReadSupport::create($file);
        $support2 = ReadSupport::create($file);

        $newSupport = new ReadSupport();
        self::assertEquals($support, $support2);
        self::assertNotEquals($newSupport, $support2);
    }

}
