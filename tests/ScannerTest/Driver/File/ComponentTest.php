<?php

namespace ScannerTest\Driver\File;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Driver\File\System\Read\ReadSupport;
use Scanner\Driver\File\System\Read\Strategy\YamlReadStrategy;
use Scanner\Driver\Support\Target\TargetSupport;

class ComponentTest extends TestCase
{

    public function testAddSupport()
    {
        $file = new File('/var/www/scanner/src/Scanner/Event/LeafListener.php');
        $file->addSupport(DummySupport::create($file));
        self::assertEquals('dummy', $file->get1('dummy'));
        self::assertEquals('get2', $file->get2());
        self::assertEquals('/var/www/scanner/src/Scanner/Event/LeafListener.php', $file->get3());
    }

    public function testExeptionCallSupportMethod()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new File('/var/www/scanner/src/Scanner/Event/LeafListener.php');
        $file->addSupport(DummySupport::create($file));
        $file->get100('dummy');
    }

    public function testSupportMethodsCalled()
    {
        $path = __DIR__ . '/test.yml';
        $path2 = __DIR__ . '/test2.yml';
        $file = new File($path);
        $file2 = new File($path2);
        $readSupport = ReadSupport::create($file);
        $readSupport->setReadStrategy(new YamlReadStrategy());

        $targetSupport = TargetSupport::create($file);
        $handler = new DummyTargetHandle();
        $targetSupport->setHandle($handler);

        $file->addSupport($readSupport);
        $file->addSupport($targetSupport);

        $file2->addSupport($readSupport);
        $file2->addSupport($targetSupport);

        $yml = $file->read();
        ob_start();
        $file->target();
        $out = ob_get_clean();

        $yml2 = $file2->read();
        ob_start();
        $file2->target();
        $out2 = ob_get_clean();

        self::assertIsArray($yml);
        self::assertArrayHasKey('version', $yml);
        self::assertEquals('45', $yml['version']);
        self::assertEquals($path, $out);

        self::assertIsArray($yml2);
        self::assertArrayHasKey('version', $yml2);
        self::assertEquals('70', $yml2['version']);
        self::assertEquals($path2, $out2);
    }
}
