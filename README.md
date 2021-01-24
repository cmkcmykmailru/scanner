Scanner
======
Простой, легкий, событийный сканер директорий.
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

#### код ExampleListener

```php
<?php

use Scanner\Event\DetectAdapter;
use Scanner\Event\NodeEvent;
use Scanner\Scanner;

class ExampleListener extends DetectAdapter
{
    private Scanner $scanner;

    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    public function leafDetected(NodeEvent $evt): void
    {
        echo $evt->getNode()->getSource() . PHP_EOL;
    }

    public function nodeDetected(NodeEvent $evt): void
    {
       $this->scanner->detect($evt->getNode()->getSource());
    }
}
```

Введите команду в консоль.

```
$ php example/index.php
```

вывод
```php
/var/www/scanner/src/Scanner/Scanner.php
/var/www/scanner/src/Scanner/Driver/ContextSupport.php
/var/www/scanner/src/Scanner/Driver/Driver.php
/var/www/scanner/src/Scanner/Driver/Leaf.php
/var/www/scanner/src/Scanner/Driver/ListenerStorage.php
/var/www/scanner/src/Scanner/Driver/ListenerSupport.php
/var/www/scanner/src/Scanner/Driver/Node.php
/var/www/scanner/src/Scanner/Driver/Normalizer.php
/var/www/scanner/src/Scanner/Driver/PropertySupport.php
/var/www/scanner/src/Scanner/Driver/File/Component.php
/var/www/scanner/src/Scanner/Driver/File/Directory.php
/var/www/scanner/src/Scanner/Driver/File/File.php
/var/www/scanner/src/Scanner/Driver/File/FileDriver.php
/var/www/scanner/src/Scanner/Driver/File/PathNodeFactory.php
/var/www/scanner/src/Scanner/Driver/File/PathNormalizer.php
/var/www/scanner/src/Scanner/Driver/File/PathParser.php
/var/www/scanner/src/Scanner/Driver/File/System/FileOperationsSupport.php
/var/www/scanner/src/Scanner/Driver/File/System/Support.php
/var/www/scanner/src/Scanner/Driver/Parser/Explorer.php
/var/www/scanner/src/Scanner/Driver/Parser/NodeFactory.php
/var/www/scanner/src/Scanner/Driver/Parser/Parser.php
/var/www/scanner/src/Scanner/Event/AbstractEvent.php
/var/www/scanner/src/Scanner/Event/DetectAdapter.php
/var/www/scanner/src/Scanner/Event/DetectEvent.php
/var/www/scanner/src/Scanner/Event/DetectListener.php
/var/www/scanner/src/Scanner/Event/Event.php
/var/www/scanner/src/Scanner/Event/LeafListener.php
/var/www/scanner/src/Scanner/Event/Listener.php
/var/www/scanner/src/Scanner/Event/NodeEvent.php
/var/www/scanner/src/Scanner/Event/NodeListener.php
/var/www/scanner/src/Scanner/Event/PropertyEvent.php
/var/www/scanner/src/Scanner/Event/PropertyListener.php
/var/www/scanner/src/Scanner/Filter/LeafFilter.php
/var/www/scanner/src/Scanner/Filter/NodeFilter.php
```

Сканер можно остановить вызвав метод $this->scanner->stop(true);
чтобы сканер снова мог сканировать нужно вызвать тот же метод, но передать ему false - $this->scanner->stop(false);

### Фильтры

В системе есть два интерфейса которые в зависимости от их реализации могут фильтровать инициализацию событий сканера

#### Scanner\Filter\LeafFilter

#### public function filterLeaf(Leaf $found): bool;
и
#### Scanner\Filter\NodeFilter
#### public function filterNode(Node $found): bool;

