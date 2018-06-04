<?php

// Setup configuration files
$appDir = dirname(__DIR__);
$srcDir = $appDir . '/src';

require $srcDir . '/library/interfaces/IRun.php';
require $srcDir . '/library/application/Micro.php';

$configPath = $appDir . '/config/';
$config = $configPath . 'config.php';
$autoLoad = $configPath . 'autoload.php';
$routes = $configPath . 'routes.php';

try {
    $app = new Minube\Application\Micro();

    $app->setAutoload($autoLoad, $srcDir);
    $app->setConfig($config);

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':
            $data = $_GET;
            unset($data['_url']);
            break;

        case 'POST':
            $data = $_POST;
            break;

        default: // PUT AND DELETE
            parse_str(file_get_contents('php://input'), $data);
            break;
    }

    $app->setRoutes($routes);
    $app->setDependencies();

    $app->run();

} catch(Exception $e) {
    $app->response->setStatusCode(500, "Server Error");
    $app->response->setContent($e->getMessage());
    $app->response->send();
}
