Scanner
======
Простой, легкий, событийный сканер директорий.
Позволяет сканировать директории и что то с ними делать.


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

require 'ExampleListener.php'; //код ниже

use Scanner\Scanner;

$scanner = new Scanner();
$driver = $scanner->getDriver();

$listener = new Exampleistener($scanner);
$driver->addNodeListener($listener);
$driver->addLeafListener($listener);
$driver->addDetectedListener($listener);

$scanner->detect(realpath(__DIR__ . '/../src'));
```

#### код ExampleListener

```php
<?php

use Scanner\Event\DetectAdapter;
use Scanner\Event\DetectEvent;
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
/var/www/scanner/src/Scanner/Driver/AbstractDriver.php
/var/www/scanner/src/Scanner/Driver/ContextSupport.php
/var/www/scanner/src/Scanner/Driver/Driver.php
/var/www/scanner/src/Scanner/Driver/File/Directory.php
/var/www/scanner/src/Scanner/Driver/File/File.php
/var/www/scanner/src/Scanner/Driver/File/FileDriver.php
/var/www/scanner/src/Scanner/Driver/Leaf.php
/var/www/scanner/src/Scanner/Driver/ListenerStorage.php
/var/www/scanner/src/Scanner/Driver/ListenerSupport.php
/var/www/scanner/src/Scanner/Driver/Node.php
/var/www/scanner/src/Scanner/Event/AbstractEvent.php
/var/www/scanner/src/Scanner/Event/DetectAdapter.php
/var/www/scanner/src/Scanner/Event/DetectEvent.php
/var/www/scanner/src/Scanner/Event/DetectListener.php
/var/www/scanner/src/Scanner/Event/Event.php
/var/www/scanner/src/Scanner/Event/LeafListener.php
/var/www/scanner/src/Scanner/Event/Listener.php
/var/www/scanner/src/Scanner/Event/NodeEvent.php
/var/www/scanner/src/Scanner/Event/NodeListener.php
/var/www/scanner/src/Scanner/Scanner.php
```
