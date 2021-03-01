Scanner
======
Сканер общего назначения. Позволяет сканировать древовидные структуры. В данный момент реализован драйвер для работы с
фалами и директориями.

### Работы еще ведутся.

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

### Базовое использование

Файл index.php 
```php
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require __DIR__ . '/../vendor/autoload.php';
require 'Visitor.php';
require 'LeafHandler.php';

use Laminas\ServiceManager\Factory\InvokableFactory;
use Scanner\Driver\File\FilesSearchSettings;
use Scanner\Driver\File\System\Read\YamlReadSupport;
use Scanner\Scanner;

/** 
 * Создание экземпляра сканера и установка зависимостей 
 * (внутри живет Laminas\ServiceManager\ServiceManager подробности https://docs.laminas.dev/laminas-servicemanager/) 
 * вы можете передать свою реализацию PSR 11.
 * LeafHandler - это обработчик целевых файлов (ваша реализация поиска внутри файла который прошел фильтры 
 * или получение другой информации о файле: дата создания и тп.)
 */
$scanner = new Scanner(['factories' => [
    LeafHandler::class => InvokableFactory::class,
]]);
/**
* Визитер ваша реализация обработки целевого файла который прошел фильтры 
 * и поиск внутри если был установлен (в данном случае LeafHandler)
 */
$visitor = new Visitor();
$scanner->setScanVisitor($visitor);

$path = realpath(__DIR__ . '/../../bigland');

$settings = new FilesSearchSettings();
$settings->search(['source' => $path])
    ->filter(['FILE' => ['extension' => 'yml']]) // установка фильтра по расширению 
    ->support(['FILE' => [YamlReadSupport::class]]) //добавление возможности парсить yml файлы 
    ->strategy([
        'handle' => [
            'leaf' => [LeafHandler::class, 'multiTarget' => true] //установка обработчика который копается внутри файла который прошел фильтры
        ]
    ]);

    $scanner->search($settings); // установка настроек и поиск

```

#### Visitor может быть таким

В данном случае печатает название файла и результат обработки LeafHandler-а, и в конце сколько всего файлов было найдено.

```php
<?php

use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\ScanVisitor;

class Visitor implements ScanVisitor
{
    private $counter = 0;

    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void {}

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        echo '______________________________' . PHP_EOL;
        echo 'Найдено файлов: ' . $this->counter . ' шт.' . PHP_EOL;
    }

    /**
     * определил лист
     * @param AbstractScanStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     */
    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->counter++;
        echo 'Найден файл: ' . $found . PHP_EOL;;
        echo $data . PHP_EOL;
        echo '====================================' . PHP_EOL;
    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void {}

}
```

#### LeafHandler может быть таким

Парсит файл, который прошел фильтры и возвращает результат или null, если вернется null, метод Visitor::visitLeaf(...) не выполнится.
```php
<?php

use Scanner\Strategy\TargetHandler;
use Scanner\Driver\Parser\NodeFactory;

class LeafHandler implements TargetHandler
{
    public function handle(NodeFactory $factory, $detect, $found)
    {
        $file = $factory->createLeaf($detect, $found);
        $yml = $file->yamlParseFile();// метод появился благодаря поддержке YamlReadSupport::class 
        $file->revokeAllSupports(); // если хотите, чтобы экземпляры удалялись из памяти вам нужно освободить их от поддержек которые назначены в версии для  php8 будет использоваться WeakMap и проблема будет решена
        if (isset($yml['version'])) {
            return 'Значение версии равно ' . $yml['version'];
        }
        return null;
    }
}
```

Пример можно запустить в консоли.

```
$ php example/index.php
```

вывод

```php
Найден файл: test.yml
Значение версии равно 45
====================================
Найден файл: test2.yml
Значение версии равно 70
====================================
Найден файл: test.yml
Значение версии равно 3.2
====================================
______________________________
Найдено файлов: 3 шт.

```
Пример реального использования вы можете посмотреть в проекте который генерирует настройки rest api в Yii2
на основе аннотаций [yii2-generator](https://github.com/cmkcmykmailru/yii2-generator), [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest) 
### Тестировать

```
composer tests
```

