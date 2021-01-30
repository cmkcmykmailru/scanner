<?php

namespace ScannerTest\Filter;

use Scanner\Driver\File\File;
use Scanner\Driver\Node;
use Scanner\Filter\Filter;
use Scanner\Filter\Verifier;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{

    public function testAppend()
    {
        $verifier = new Verifier();
        $verifier->append(new class() implements Filter {
            public function filter(Node $node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }
        })->append(new class() implements Filter {
            public function filter(Node $node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }
        })->append(new class() implements Filter {
            public function filter(Node $node): bool
            {
                return $node->getSource() === 'conftest1.php';
            }
        });

        $coolFile = new File('conftest1.php');
        $badFile = new File('conftest1.yml');
        $badFile2 = new File('contest1.yml');

        self::assertEquals(true, $verifier->can($coolFile));
        self::assertEquals(false, $verifier->can($badFile));
        self::assertEquals(false, $verifier->can($badFile2));

        $verifier2 = new Verifier();
        $verifier2->append(new class() implements Filter {
            public function filter(Node $node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }
        })->append(new class() implements Filter {
            public function filter(Node $node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }
        });
        $coolFile2 = new File('conf.php');
        $badFile3 = new File('conf.dhp');
        self::assertEquals(true, $verifier2->can($coolFile2));
        self::assertEquals(false, $verifier2->can($badFile3));

        $verifier3 = new Verifier();//фильтров нет, вернул true
        self::assertEquals(true, $verifier3->can($coolFile2));
        self::assertEquals(true, $verifier3->can($badFile3));
    }
}
