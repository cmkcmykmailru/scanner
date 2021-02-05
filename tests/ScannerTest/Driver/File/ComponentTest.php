<?php

namespace ScannerTest\Driver\File;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Driver\File\System\Read\YamlReadSupport;
use Scanner\Driver\File\System\Read\Strategy\YamlReadStrategy;
use Scanner\Driver\Support\Target\TargetSupport;

class ComponentTest extends TestCase
{

    public function testAddSupport()
    {
        $file = new File('/var/www/scanner/src/Scanner/Event/LeafListener.php');
        $file->assignSupport(DummySupport::create($file));
        self::assertEquals('dummy', $file->get1('dummy'));
        self::assertEquals('get2', $file->get2());
        self::assertEquals('/var/www/scanner/src/Scanner/Event/LeafListener.php', $file->get3());
    }

    public function testExeptionCallSupportMethod()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new File('/var/www/scanner/src/Scanner/Event/LeafListener.php');
        $file->assignSupport(DummySupport::create($file));
        $file->get100('dummy');
    }

    public function testSupportMethodsCalled()
    {
        $path = __DIR__ . '/test.yml';
        $path2 = __DIR__ . '/test2.yml';
        $file = new File($path);
        $file2 = new File($path2);
        $readSupport = YamlReadSupport::create($file);

        $file->assignSupport($readSupport);

        $file2->assignSupport($readSupport);


        $yml = $file->yamlParseFile();


        $yml2 = $file2->yamlParseFile();
        self::assertIsArray($yml);
        self::assertArrayHasKey('version', $yml);
        self::assertEquals('45', $yml['version']);


        self::assertIsArray($yml2);
        self::assertArrayHasKey('version', $yml2);
        self::assertEquals('70', $yml2['version']);

    }
}
