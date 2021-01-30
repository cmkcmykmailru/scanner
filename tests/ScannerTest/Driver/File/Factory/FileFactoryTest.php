<?php

namespace ScannerTest\Driver\File\Factory;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Scanner\Driver\File\Directory;
use Scanner\Driver\File\File;
use Scanner\Driver\File\FileFactory;
use Scanner\Driver\File\System\Read\ReadSupport;
use ScannerTest\Driver\File\DummySupport;

class FileFactoryTest extends TestCase
{
    public function testNeedSupportsOf()
    {
        $supportSetting = [
            'FILE' => [ReadSupport::class],
            'DIRECTORY' => [DummySupport::class]
        ];

        $fileFactory = new FileFactory();
        $fileFactory->needSupportsOf($supportSetting);

        $reflection = new ReflectionObject($fileFactory);

        $fileSupports = $reflection->getProperty('fileSupports');
        $fileSupports->setAccessible(true);
        $fileSupportsValue = $fileSupports->getValue($fileFactory);

        self::assertIsArray($fileSupportsValue);
        self::assertContains(ReadSupport::class, $fileSupportsValue);

        $directorySupports = $reflection->getProperty('directorySupports');
        $directorySupports->setAccessible(true);
        $directorySupportsValue = $directorySupports->getValue($fileFactory);

        self::assertIsArray($directorySupportsValue);
        self::assertContains(DummySupport::class, $directorySupportsValue);

        self::assertNotContains(DummySupport::class, $fileSupportsValue);
        self::assertNotContains(ReadSupport::class, $directorySupportsValue);

        self::assertCount(1, $fileSupportsValue);
        self::assertCount(1, $directorySupportsValue);
    }

    public function testCreateLeaf()
    {
        $supportSetting = [
            'FILE' => [DummySupport::class]
        ];
        $fileFactory = new FileFactory();
        $detect = 'var/to/path';
        $fileName = 'test.php';
        $file = $fileFactory->createLeaf($detect, $fileName);
        self::assertEquals(File::class, get_class($file));
        self::assertEquals($detect . DIRECTORY_SEPARATOR . $fileName, $file->getSource());

        $fileFactory->needSupportsOf($supportSetting);
        $file2 = $fileFactory->createLeaf($detect, $fileName);
        self::assertEquals('dummy', $file2->get1('dummy'));
    }

    public function testCreateNode()
    {
        $supportSetting = [
            'DIRECTORY' => [DummySupport::class]
        ];
        $fileFactory = new FileFactory();
        $detect = 'var/to/path';
        $directoryName = 'folder';
        $directory = $fileFactory->createNode($detect, $directoryName);
        self::assertEquals(Directory::class, get_class($directory));
        self::assertEquals($detect . DIRECTORY_SEPARATOR . $directoryName, $directory->getSource());

        $fileFactory->needSupportsOf($supportSetting);
        $directory2 = $fileFactory->createNode($detect, $directoryName);
        self::assertEquals('dummy', $directory2->get1('dummy'));
    }
}
