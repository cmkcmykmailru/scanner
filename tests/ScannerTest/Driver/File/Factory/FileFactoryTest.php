<?php

namespace ScannerTest\Driver\File\Factory;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Scanner\Driver\ContextSupport;
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

        $filePrototype = $reflection->getProperty('filePrototype');
        $filePrototype->setAccessible(true);
        $filePrototypeValue = $filePrototype->getValue($fileFactory);

        self::assertEquals(File::class, get_class($filePrototypeValue));

        $support = ContextSupport::getFunctionalitySupport($filePrototypeValue);

        $reflectionSupport = new ReflectionObject($support);
        $storageProp = $reflectionSupport->getProperty('storage');
        $storageProp->setAccessible(true);
        $storage = $storageProp->getValue($support);
        $sup = ReadSupport::create($filePrototypeValue);
        self::assertContains($sup, $storage);

        self::assertCount(1, $storage);//1 method ReadSupport

        $directoryProperty = $reflection->getProperty('directoryPrototype');
        $directoryProperty->setAccessible(true);
        $directoryPrototype = $directoryProperty->getValue($fileFactory);

        $dirSupport = ContextSupport::getFunctionalitySupport($directoryPrototype);

        $reflectionSupport = new ReflectionObject($dirSupport);
        $storageProp = $reflectionSupport->getProperty('storage');
        $storageProp->setAccessible(true);
        $storage = $storageProp->getValue($dirSupport);
        $sup = DummySupport::create($directoryPrototype);

        self::assertContains($sup, $storage);
        self::assertCount(3, $storage);//3 methods DummySupport

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


        $file2Support = ContextSupport::getFunctionalitySupport($file2);

        $reflectionSupport = new ReflectionObject($file2Support);
        $storageProp = $reflectionSupport->getProperty('storage');
        $storageProp->setAccessible(true);
        $storageFile2 = $storageProp->getValue($file2Support);


        $reflection = new ReflectionObject($fileFactory);

        $fileProperty = $reflection->getProperty('filePrototype');
        $fileProperty->setAccessible(true);
        $filePrototype = $fileProperty->getValue($fileFactory);
        $filePrototypeSupport = ContextSupport::getFunctionalitySupport($filePrototype);

        $reflectionFilePrototypeSupport = new ReflectionObject($filePrototypeSupport);
        $filePrototypeStorageProp = $reflectionFilePrototypeSupport->getProperty('storage');
        $filePrototypeStorageProp->setAccessible(true);
        $storageFilePrototype = $filePrototypeStorageProp->getValue($filePrototypeSupport);

        self::assertEquals($storageFile2, $storageFilePrototype);

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


        $directory2Support = ContextSupport::getFunctionalitySupport($directory2);

        $reflectionSupport = new ReflectionObject($directory2Support);
        $storageProp = $reflectionSupport->getProperty('storage');
        $storageProp->setAccessible(true);
        $storageDirectory2 = $storageProp->getValue($directory2Support);

        $reflection = new ReflectionObject($fileFactory);

        $directoryPrototypeProperty = $reflection->getProperty('directoryPrototype');
        $directoryPrototypeProperty->setAccessible(true);
        $directoryPrototype = $directoryPrototypeProperty->getValue($fileFactory);
        $directoryPrototypeSupport = ContextSupport::getFunctionalitySupport($directoryPrototype);

        $reflectionDirectoryPrototypeSupport = new ReflectionObject($directoryPrototypeSupport);
        $directoryPrototypeStorageProp = $reflectionDirectoryPrototypeSupport->getProperty('storage');
        $directoryPrototypeStorageProp->setAccessible(true);
        $storageDirectoryPrototype = $directoryPrototypeStorageProp->getValue($directoryPrototypeSupport);

        self::assertEquals($storageDirectory2, $storageDirectoryPrototype);
    }
}
