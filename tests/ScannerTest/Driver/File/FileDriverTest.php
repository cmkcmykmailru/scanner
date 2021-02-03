<?php

namespace ScannerTest\Driver\File;

use Psr\Container\ContainerInterface;
use Scanner\Driver\File\FileDriver;
use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\FileFactory;
use Scanner\Driver\File\Filter\ExtensionFilter;
use Scanner\Driver\File\Filter\PrefixFilter;
use Scanner\Driver\Node;
use Scanner\Filter\Filter;

class FileDriverTest extends TestCase
{

    public function testResolveLeafFilters()
    {
        $factory = $this->createMock(FileFactory::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn(new class() implements Filter {
                public function filter( $node): bool
                {
                    return $node === 'conftest1.php';
                }
            });
        $container->method('has')
            ->willReturn(true);

        $fileDriver = new FileDriver($factory);
        $filters = $fileDriver->resolveLeafFilters([
            'FILE' => ['extension' => 'yml', 'prefix' => 'yml', Filter::class],
            'DIRECTORY' => [Filter::class]
        ], $container);
        foreach ($filters as $filter) {
            self::assertEquals(true, isset(class_implements(get_class($filter))[Filter::class]));
        }

        $filters = $fileDriver->resolveLeafFilters([
            'FILE' => [Filter::class],
            'DIRECTORY' => [Filter::class]
        ], $container);

        foreach ($filters as $filter) {
            self::assertEquals(true, isset(class_implements(get_class($filter))[Filter::class]));
        }

        $filters = $fileDriver->resolveLeafFilters([
            'FILE' => ['extension' => 'yml']
        ], $container);
        self::assertEquals(ExtensionFilter::class, get_class($filters->current()));

        $filters = $fileDriver->resolveLeafFilters([
            'FILE' => ['prefix' => 'yml']
        ], $container);

        self::assertEquals(PrefixFilter::class, get_class($filters->current()));
    }

    public function testResolveNodeFilters()
    {
        $factory = $this->createMock(FileFactory::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn(new class() implements Filter {
                public function filter($node): bool
                {
                    return $node === 'conftest1.php';
                }
            });
        $container->method('has')
            ->willReturn(true);

        $fileDriver = new FileDriver($factory);
        $filters = $fileDriver->resolveNodeFilters([
            'FILE' => ['extension' => 'yml', 'prefix' => 'yml', Filter::class],
            'DIRECTORY' => [Filter::class]
        ], $container);
        foreach ($filters as $filter) {
            self::assertEquals(true, isset(class_implements(get_class($filter))[Filter::class]));
        }

        $filters = $fileDriver->resolveNodeFilters([
            'FILE' => [Filter::class],
            'DIRECTORY' => [Filter::class]
        ], $container);

        foreach ($filters as $filter) {
            self::assertEquals(true, isset(class_implements(get_class($filter))[Filter::class]));
        }
    }
}
