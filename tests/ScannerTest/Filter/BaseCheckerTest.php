<?php

namespace ScannerTest\Filter;

use PHPUnit\Framework\TestCase;
use Scanner\Driver\File\File;
use Scanner\Driver\Node;
use Scanner\Filter\BaseChecker;
use Scanner\Filter\Filter;

class BaseCheckerTest extends TestCase
{

    public function testCan()
    {
        $call1 = new class() implements Filter {
            public function filter(Node $node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }
        };

        $call2 = new class() implements Filter {
            public function filter(Node $node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }
        };

        $call3 = new class() implements Filter {
            public function filter(Node $node): bool
            {
                return $node->getSource() === 'conftest1.php';
            }
        };

        $coolFile = new File('conftest1.php');
        $badFile = new File('conftest1.yml');

        $check = new BaseChecker($call1);

        $check->append(new BaseChecker($call2))->append(new BaseChecker($call3));

        self::assertEquals(true, $check->can($coolFile));
        self::assertEquals(false, $check->can($badFile));

        $check2 = new BaseChecker($call1);
        $check2->append(new BaseChecker($call2));
        $coolFile2 = new File('conf_tedsghdghdfgh.php');

        self::assertEquals(true, $check2->can($coolFile));
        self::assertEquals(false, $check2->can($badFile));
        self::assertEquals(true, $check2->can($coolFile2));

    }
}
