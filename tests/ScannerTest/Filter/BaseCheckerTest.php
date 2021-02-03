<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Filter\BaseChecker;
use Scanner\Filter\Filter;

class BaseCheckerTest extends TestCase
{

    public function testCan()
    {
        $call1 = new class() implements Filter {
            public function filter($node): bool
            {
                $pathInfo = pathinfo($node);
                return 'php' === $pathInfo['extension'];
            }
        };

        $call2 = new class() implements Filter {
            public function filter($node): bool
            {
                $sub = substr($node, 0, 4);
                return $sub === 'conf';
            }
        };

        $call3 = new class() implements Filter {
            public function filter($node): bool
            {
                return $node === 'conftest1.php';
            }
        };

        $coolFile = 'conftest1.php';
        $badFile = 'conftest1.yml';

        $check = new BaseChecker($call1);

        $check->append(new BaseChecker($call2))->append(new BaseChecker($call3));

        self::assertEquals(true, $check->can($coolFile));
        self::assertEquals(false, $check->can($badFile));

        $check2 = new BaseChecker($call1);
        $check2->append(new BaseChecker($call2));
        $coolFile2 ='conf_tedsghdghdfgh.php';

        self::assertEquals(true, $check2->can($coolFile));
        self::assertEquals(false, $check2->can($badFile));
        self::assertEquals(true, $check2->can($coolFile2));

    }
}
