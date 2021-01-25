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
        $file = new File(__DIR__ . '/test.yml');
        $readSupport = ReadSupport::create($file);
        $readSupport->setReadStrategy(new YamlReadStrategy());

        $targetSupport = TargetSupport::create($file);
        $handler = new DummyTargetHandle();
        $targetSupport->setHandle($handler);

        $file->addSupport($readSupport);
        $file->addSupport($targetSupport);

        $yml = $file->read();
        ob_start();
        $file->target();
        $out = ob_get_clean();

        self::assertIsArray($yml);
        self::assertArrayHasKey('version', $yml);
        self::assertEquals('45', $yml['version']);
        self::assertEquals('target', $out);
    }
}
