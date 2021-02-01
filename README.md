Scanner
======
Сканер директорий.
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
require 'Visitor.php';

use Scanner\Driver\File\FilesSearchSettings;
use Scanner\Driver\File\System\Read\ReadSupport;
use Scanner\Scanner;

$scanner = new Scanner();
$visitor = new Visitor();
$scanner->setScanVisitor($visitor);

$path = realpath(__DIR__ . '/../tests');

$settings = new FilesSearchSettings();
$settings->search(['ALL', 'source' => $path])
    ->filter(['FILE' => ['extension' => 'yml']])
    ->support(['FILE' => [ReadSupport::class]]);

$scanner->search($settings);

```

#### Слушатель может быть таким

В данном случае просто ищет файлы с раcширением yml читает и печатает содержимое.
код Visitor:

```php
<?php

use Scanner\Strategy\ScanVisitor;
use Scanner\Event\DetectEvent;
use Scanner\Event\NodeEvent;

class Visitor implements ScanVisitor
{

    public function detectStarted(DetectEvent $evt): void {}

    public function detectCompleted(DetectEvent $evt): void {}

    /**
     * определил лист
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
     * определил ноду
     * @param NodeEvent $evt
     */
    public function nodeDetected(NodeEvent $evt): void {}

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

