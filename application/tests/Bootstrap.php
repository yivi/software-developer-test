<?php
namespace MinubeTests;

use Phalcon\DI;
use Phalcon\DI\FactoryDefault;

define( 'APP_ROOT_PATH', dirname( __DIR__ ) );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

set_include_path(
	__DIR__ . PATH_SEPARATOR . get_include_path()
);

// required for phalcon/incubator
include APP_ROOT_PATH . "/vendor/autoload.php";

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Loader();

$namespaces = [
	'MinubeTests'        => __DIR__,
	'Minube\Application' => APP_ROOT_PATH . '/src/library/application/',
	'Minube\Interfaces'  => APP_ROOT_PATH . '/src/library/interfaces/',
	'Minube\Controllers' => APP_ROOT_PATH . '/src/controllers/',
	'Minube\Models'      => APP_ROOT_PATH . '/src/models/',
];

// get namespaces from Composer
$map = require __DIR__ . '/../vendor/composer/autoload_namespaces.php';

foreach ( $map as $namespace => $values ) {
	$namespace = trim( $namespace, '\\' );
	if ( ! isset( $namespaces[ $namespace ] ) ) {
		$namespaces[ $namespace ] = $values[0];
	}
}
$loader->registerNamespaces( $namespaces );

$loader->register();

$di = new FactoryDefault();
DI::reset();

// add any needed services to the DI here

DI::setDefault( $di );
