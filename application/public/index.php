<?php

// Setup configuration files
define( 'APP_ROOT_PATH', dirname( __DIR__ ) );
$config   = APP_ROOT_PATH . '/config/config.php';
$autoLoad = APP_ROOT_PATH . '/config/autoload.php';
$routes   = APP_ROOT_PATH . '/config/routes.php';

require APP_ROOT_PATH . '/src/library/interfaces/IRun.php';
require APP_ROOT_PATH . '/src/library/application/Micro.php';

try {
	$app = new Minube\Application\Micro();

	$app->setAutoload( require $autoLoad );
	$app->setConfig( require $config );
	$app->setRoutes( require $routes );

	$app->setDependencies();

	$app->run();

} catch ( Exception $e ) {
	$app->response->setStatusCode( 500, "Server Error" );
	$app->response->setContent( $e->getMessage() );
	$app->response->send();
}
