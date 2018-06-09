<?php

namespace Minube\Application;

use Exception;
use Minube\Interfaces\IRun;
use Minube\Models\Pois;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Cache\Frontend\Data as FrontendData;

class Micro extends \Phalcon\Mvc\Micro implements IRun {

	/**
	 * Set Dependency Injector with configuration variables
	 *
	 * @throws Exception        on bad database adapter
	 *
	 * @param array $config full path to configuration file
	 */
	public function setConfig( $config ) {

		$di = new \Phalcon\DI\FactoryDefault();
		$di->set( 'config', new \Phalcon\Config( $config ) );

		$di->set( 'db', function () use ( $di ) {
			$configuration = [
				'host'     => $di->get( 'config' )->database->host,
				'username' => $di->get( 'config' )->database->username,
				'password' => $di->get( 'config' )->database->password,
				'dbname'   => $di->get( 'config' )->database->name,
			];

			return new \Phalcon\Db\Adapter\Pdo\Mysql( $configuration );
		} );

		$di->set( 'modelsCache', function () use ( $di ) {
			$frontCache = new FrontendData( [
				"lifetime" => 172800,
			] );

			return new Redis( $frontCache, [
				'host' => $di->get( 'config' )->redis->host,
				'port' => $di->get( 'config' )->redis->port,
			] );
		} );


		$this->setDI( $di );
	}

	/**
	 * Set namespaces to traverse through in the autoloader
	 *
	 * @link http://docs.phalconphp.com/en/latest/reference/loader.html
	 *
	 * @param array $namespaces map of namespace to directories
	 */
	public function setAutoload( array $namespaces ) {
		$loader = new \Phalcon\Loader();
		$loader->registerNamespaces( $namespaces )->register();
	}

	/**
	 * Set Routes\Handlers for the application
	 *
	 * @param array $routes file path with the array of routes to load
	 *
	 * @throws \Exception
	 */
	public function setRoutes( array $routes ) {

		if ( ! empty( $routes ) ) {
			foreach ( $routes as $obj ) {
				$controllerName = class_exists( $obj['handler'][0] ) ? $obj['handler'][0] : false;
				if ( ! $controllerName ) {
					throw new \Exception( "Wrong controller name in routes ({$obj['handler'][0]})" );
				}

				$controller       = new $controllerName;
				$controllerAction = $obj['handler'][1];

				switch ( $obj['method'] ) {
					case 'get':
						$this->get( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'post':
						$this->post( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'delete':
						$this->delete( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'put':
						$this->put( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'head':
						$this->head( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'options':
						$this->options( $obj['route'], [ $controller, $controllerAction ] );
						break;
					case 'patch':
						$this->patch( $obj['route'], [ $controller, $controllerAction ] );
						break;
					default:
						break;
				}

			}
		}
	}

	/**
	 * Set events to be triggered before/after certain stages in Micro App
	 *
	 * @param \Phalcon\Events\Manager $events
	 *
	 * @internal param object $event events to add
	 */
	public function setEvents( \Phalcon\Events\Manager $events ) {
		$this->setEventsManager( $events );
	}

	/**
	 * Main run block that executes the micro application
	 *
	 */
	public function run() {

		// Handle any routes not found
		$this->notFound( function () {
			$response = new \Phalcon\Http\Response();
			$response->setStatusCode( 404, 'Not Found' )->sendHeaders();
			$response->setContent( 'Page doesn\'t exist.' );
			$response->send();
		} );

		$this->handle();
	}

	public function setDependencies() {
		$this->getDI()->set( Pois::class, function () {
			return new Pois();
		} );
	}
}
