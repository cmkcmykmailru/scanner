Scanner
======
Событийный сканер директорий.
Позволяет сканировать директории и что-то с ними делать.


Установка
------------

Предпочтительный способ установки этого расширения - через [composer](http://getcomposer.org/download/).

Запустите команду

```
php composer.phar require --prefer-dist grigor/scanner "*"
```

или добавьте в composer.json

```
"grigor/scanner": "*"
```

### Пример

```php
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require __DIR__ . '/../vendor/autoload.php';
require 'ExampleListener.php';

use Scanner\Scanner;

$scanner = new Scanner();
$scanner->addDetectAdapter(new ExampleListener($scanner));

$scanner->detect(realpath(__DIR__ . '/../src'));
```

#### Слушатель может быть таким

код ExampleListener:

```php
<?php

use Scanner\Event\DetectAdapter;
use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;
use Scanner\Scanner;

class Exampleistener extends DetectAdapter
{
    private Scanner $scanner;
    private int $count2 = 0;
    private $nodes = [];

    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    public function detectStarted(DetectEvent $evt): void
    {
        $this->printOffset($evt->getDetect());
        $this->count2++;
        echo basename($evt->getDetect()) . '/' . PHP_EOL;
    }

    public function detectCompleted(DetectEvent $evt): void
    {
        $arr = array_shift($this->nodes);

        if ($arr === null) {
            return;
        }
        foreach ($arr as $node) {
            $this->scanner->detect($node->getSource());
        }
    }

    /**
     * определил файл
     * @param NodeEvent $evt
     */
    public function leafDetected(NodeEvent $evt): void
    {
        $this->printOffset($evt->getNode()->getSource());
        echo basename($evt->getNode()->getSource()) . PHP_EOL;
    }

    /**
     * определил папку
     * @param NodeEvent $evt
     */
    public function nodeDetected(NodeEvent $evt): void
    {
        $this->nodes[$this->count2][] = $evt->getNode();
    }

    private function printOffset(string $path): void
    {
        $arr = explode(DIRECTORY_SEPARATOR, $path);
        if (!$arr) {
            return;
        }

        $c = count($arr);
        for ($i = 0; $i < $c; $i++) {
            echo '-';
        }
    }
}
```

Введите команду в консоль.

```
$ php example/index.php
```

вывод
```php
-----src/
------Scanner/
-------Scanner.php
-------Driver/
--------ContextSupport.php
--------Driver.php
--------FunctionalitySupport.php
--------Leaf.php
--------ListenerStorage.php
--------ListenerSupport.php
--------Node.php
--------Normalizer.php
--------PropertySupport.php
--------File/
---------Component.php
---------Directory.php
---------File.php
---------FileDriver.php
---------Path.php
---------PathNodeFactory.php
---------PathNormalizer.php
---------PathParser.php
---------System/
----------AbstractSupport.php
----------FileOperationsSupport.php
----------Support.php
--------Parser/
---------Explorer.php
---------NodeFactory.php
---------Parser.php
-------Event/
--------AbstractEvent.php
--------CallMethodEvent.php
--------DetectAdapter.php
--------DetectEvent.php
--------DetectListener.php
--------Event.php
--------LeafListener.php
--------Listener.php
--------MethodCallListener.php
--------NodeEvent.php
--------NodeListener.php
--------PropertyEvent.php
--------PropertyListener.php
-------Filter/
--------LeafFilter.php
--------NodeFilter.php
```

Сканер можно остановить вызвав метод $this->scanner->stop(true);
чтобы сканер снова мог сканировать нужно вызвать тот же метод, но передать ему false - $this->scanner->stop(false);

### Фильтры

В системе есть два интерфейса которые в зависимости от их реализации могут фильтровать инициализацию событий сканера

Документация будет, когда проект будет завершен.

