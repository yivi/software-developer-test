<?php
use Phalcon\DI;
use Phalcon\DI\FactoryDefault;

ini_set('display_errors', 1);
error_reporting(E_ALL);

set_include_path(
    __DIR__ . PATH_SEPARATOR . get_include_path()
);

// required for phalcon/incubator
include __DIR__ . "/../../vendor/autoload.php";

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Loader();

$appDir = dirname(__DIR__);
$srcDir = $appDir . '/../src';

$namespaces = array(
    'MinubeTests' => __DIR__ . '/tests/',
    'Minube\Application' => $srcDir . '/library/application/',
    'Minube\Interfaces' => $srcDir . '/library/interfaces/',
    'Minube\Controllers' => $srcDir . '/controllers/',
    'Minube\Models' => $srcDir . '/models/',
);

// get namespaces from Composer
$map = require __DIR__ . '/../../vendor/composer/autoload_namespaces.php';

foreach ($map as $namespace => $values) {
    $namespace = trim($namespace, '\\');
    if (!isset($namespaces[$namespace])) {
        $namespaces[$namespace] = $values[0];
    }
}
$loader->registerNamespaces($namespaces);

$loader->registerDirs(array(
    __DIR__ . '/../src/',
    __DIR__ . '/tests/',
));

$loader->register();

$di = new FactoryDefault();
DI::reset();

// add any needed services to the DI here

DI::setDefault($di);
