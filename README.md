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

use Scanner\Driver\File\FilesSearchSettings;
use Scanner\Driver\File\System\Read\ReadSupport;
use Scanner\Scanner;


$scanner = new Scanner();
$listener = new ExampleListener($scanner); //код ниже
$scanner->addDetectAdapter($listener);

$path = realpath(__DIR__ . '/../tests');

$settings = new FilesSearchSettings();
$settings->search(['ALL', 'source' => $path])
    ->filter(['FILE' => ['extension' => 'yml']])
    ->support(['FILE' => [ReadSupport::class]]);

$scanner->search($settings);
```

#### Слушатель может быть таким

В данном случае просто печатает название папок и файлов
код ExampleListener:

```php
<?php

use Scanner\Event\DetectAdapter;
use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;
use Scanner\Scanner;

class ExampleListener extends DetectAdapter
{
    private Scanner $scanner;
    private int $counter = 0;
    private $nodes = [];

    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    public function detectStarted(DetectEvent $evt): void
    {
        $this->counter++;
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
        echo 'Найден файл: ' ;
        echo basename($evt->getNode()->getSource()) . PHP_EOL;
        $yml = $evt->getNode()->read();

        echo 'Содержит:' . PHP_EOL;
        print_r($yml);
        echo '====================================' . PHP_EOL;
    }

    /**
     * определил папку
     * @param NodeEvent $evt
     */
    public function nodeDetected(NodeEvent $evt): void
    {
        $this->nodes[$this->counter][] = $evt->getNode();
    }

}
```

Введите команду в консоль.

```
$ php example/index.php
```

вывод
```php
Найден файл: test.yml
Содержит:
Array
(
    [version] => 45
)
====================================
Найден файл: test2.yml
Содержит:
Array
(
    [version] => 70
)
====================================
Найден файл: test.yml
Содержит:
Array
(
    [version] => 3.2
)
====================================

```

Сканер можно остановить вызвав метод $this->scanner->stop(true);
чтобы сканер снова мог сканировать нужно вызвать тот же метод, но передать ему false - $this->scanner->stop(false);

### Фильтры

В системе есть два интерфейса которые в зависимости от их реализации могут фильтровать инициализацию событий сканера

Документация будет, когда проект будет завершен.

### Тестировать 
```
composer tests
```

