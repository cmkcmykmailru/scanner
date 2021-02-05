<?php

namespace ScannerTest\Driver\File\System\Read;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Driver\File\System\Read\YamlReadSupport;
use Scanner\Exception\NotSupportedArgumentException;

class ReadSupportTest extends TestCase
{

    public function testRead()
    {
        $file = new File(__DIR__ . '/test.yml');
        $support = YamlReadSupport::create($file);

        $yml = $support->yamlParseFile($file);
        self::assertIsArray($yml);
        self::assertArrayHasKey('version', $yml);
        self::assertEquals('3.2', $yml['version']);
    }

    public function testCreateReadSupport()
    {
        $file = new File(__DIR__ . '/test.yml');
        $support = YamlReadSupport::create($file);
        $support2 = YamlReadSupport::create($file);

        $newSupport = new YamlReadSupport();

        self::assertSame($support, $support2);
        self::assertNotSame($newSupport, $support2);
    }

    public function testExcepAruments()
    {
        $this->expectException(NotSupportedArgumentException::class);

        $file = new File(__DIR__ . '/test.yml');
        $file->assignSupport(YamlReadSupport::create($file));
        $file->yamlParseFile('exception');
    }

    public function testAruments()
    {
        $file = new File(__DIR__ . '/test.yml');
        $file->assignSupport(YamlReadSupport::create($file));

        self::assertIsArray($file->yamlParseFile());
    }
}
