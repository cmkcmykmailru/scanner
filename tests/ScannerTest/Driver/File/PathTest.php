<?php

namespace ScannerTest\Driver\File;

use ReflectionObject;
use Scanner\Driver\File\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testSplit()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $pathStrTest = '/src/Scanner/Driver/File';
        $path = new Path($pathStr);
        self::assertEquals($pathStrTest, $path->getSource());
        self::assertEquals('File', $path->getBaseName());

        $reflection = new ReflectionObject($path);
        $parts = $reflection->getProperty('parts');
        $parts->setAccessible(true);
        $partsValue = $parts->getValue($path);

        self::assertEquals('/src/Scanner/Driver/File', $partsValue['File']);
        self::assertEquals('/src/Scanner/Driver', $partsValue['Driver']);
        self::assertEquals('/src/Scanner', $partsValue['Scanner']);
        self::assertEquals('/src', $partsValue['src']);
    }

    public function testParent()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);
        $parent = $path1->parent();

        $pathStrTest = '/src/Scanner/Driver';

        self::assertEquals($pathStrTest, $parent->getSource());
        self::assertEquals('Driver', $parent->getBaseName());

        $reflection = new ReflectionObject($parent);
        $parts = $reflection->getProperty('parts');
        $parts->setAccessible(true);
        $partsValue = $parts->getValue($parent);

        self::assertEquals('/src/Scanner/Driver', $partsValue['Driver']);
        self::assertEquals('/src/Scanner', $partsValue['Scanner']);
        self::assertEquals('/src', $partsValue['src']);
    }

    public function testChild()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);
        $child = $path1->child('NewFile');

        $pathStrTest = '/src/Scanner/Driver/File/NewFile';

        self::assertEquals($pathStrTest, $child->getSource());
        self::assertEquals('NewFile', $child->getBaseName());

        $reflection = new ReflectionObject($child);
        $parts = $reflection->getProperty('parts');
        $parts->setAccessible(true);
        $partsValue = $parts->getValue($child);

        self::assertEquals('/src/Scanner/Driver/File/NewFile', $partsValue['NewFile']);
        self::assertEquals('/src/Scanner/Driver/File', $partsValue['File']);
        self::assertEquals('/src/Scanner/Driver', $partsValue['Driver']);
        self::assertEquals('/src/Scanner', $partsValue['Scanner']);
        self::assertEquals('/src', $partsValue['src']);
    }

    public function testEqualsBaseName()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);

        self::assertEquals(true, $path1->equalsBaseName('File'));
    }

    public function testEqualsPartOfPath()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);

        self::assertEquals(false, $path1->equalsPartOfPath('/src/Scanner/Driver/File/sdghgh'));
        self::assertEquals(true, $path1->equalsPartOfPath('/src/Scanner/Driver/File'));
        self::assertEquals(true, $path1->equalsPartOfPath('/src/Scanner/Driver/File'));
        self::assertEquals(true, $path1->equalsPartOfPath('/src/Scanner/Driver'));
        self::assertEquals(true, $path1->equalsPartOfPath('/src/Scanner'));
        self::assertEquals(true, $path1->equalsPartOfPath('/src'));
    }

    public function testEquals()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);

        self::assertEquals(false, $path1->equals('File1'));
        self::assertEquals(true, $path1->equals('File'));
    }

    public function testContainsPartOfPath()
    {
        $pathStr = '/src/Scanner/Driver/File/';
        $path1 = new Path($pathStr);

        self::assertEquals(false, $path1->containsPartOfPath('File1'));
        self::assertEquals(true, $path1->containsPartOfPath('File'));
        self::assertEquals(true, $path1->containsPartOfPath('Driver'));
        self::assertEquals(true, $path1->containsPartOfPath('Scanner'));
        self::assertEquals(true, $path1->containsPartOfPath('src'));
        self::assertEquals(true, $path1->containsPartOfPath('/'));
    }
}
