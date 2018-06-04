<?php

namespace Minube\Application;

use Exception;
use Minube\Interfaces\IRun;

class Micro extends \Phalcon\Mvc\Micro implements IRun
{

    /**
     * Set Dependency Injector with configuration variables
     *
     * @throws Exception        on bad database adapter
     * @param string $file full path to configuration file
     */
    public function setConfig($file)
    {
        if (!file_exists($file)) {
            throw new \Exception('Unable to load configuration file');
        }

        $di = new \Phalcon\DI\FactoryDefault();
        $di->set('config', new \Phalcon\Config(require $file));

        $di->set('db', function () use ($di) {
            $credentials = array(
                'host' => $di->get('config')->database->host,
                'username' => $di->get('config')->database->username,
                'password' => $di->get('config')->database->password,
                'dbname' => $di->get('config')->database->name
            );

            return new \Phalcon\Db\Adapter\Pdo\Mysql($credentials);
        });

        $di->set('redis', function () use ($di) {
            $frontCache = new \Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 172800
            ));

            return new \Phalcon\Cache\Backend\Redis($frontCache, array(
                'host' => $di->get('config')->redis->host,
                'port' => $di->get('config')->redis->port
            ));
        });

        $this->setDI($di);
    }

    /**
     * Set namespaces to tranverse through in the autoloader
     *
     * @link http://docs.phalconphp.com/en/latest/reference/loader.html
     * @throws Exception
     * @param string $file map of namespace to directories
     */
    public function setAutoload($file, $dir)
    {
        if (!file_exists($file)) {
            throw new \Exception('Unable to load autoloader file');
        }

        // Set dir to be used inside include file
        $namespaces = include $file;

        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces($namespaces)->register();
    }

    /**
     * Set Routes\Handlers for the application
     *
     * @throws Exception
     * @param string $file file path with the array of routes to load
     */
    public function setRoutes($file)
    {
        if (!file_exists($file)) {
            throw new \Exception('Unable to load routes file');
        }

        $routes = include($file);

        if (!empty($routes)) {
            foreach ($routes as $obj) {
                $controllerName = class_exists($obj['handler'][0]) ? $obj['handler'][0] : false;
                if (!$controllerName) {
                    throw new \Exception("Wrong controller name in routes ({$obj['handler'][0]})");
                }

                $controller = new $controllerName;
                $controllerAction = $obj['handler'][1];

                switch ($obj['method']) {
                    case 'get':
                        $this->get($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'post':
                        $this->post($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'delete':
                        $this->delete($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'put':
                        $this->put($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'head':
                        $this->head($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'options':
                        $this->options($obj['route'], array($controller, $controllerAction));
                        break;
                    case 'patch':
                        $this->patch($obj['route'], array($controller, $controllerAction));
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
     * @internal param object $event events to add
     */
    public function setEvents(\Phalcon\Events\Manager $events)
    {
        $this->setEventsManager($events);
    }

    /**
     * Main run block that executes the micro application
     *
     */
    public function run()
    {

        // Handle any routes not found
        $this->notFound(function () {
            $response = new \Phalcon\Http\Response();
            $response->setStatusCode(404, 'Not Found')->sendHeaders();
            $response->setContent('Page doesn\'t exist.');
            $response->send();
        });

        $this->handle();
    }

    public function setDependencies()
    {
        $this->getDI()->set('Minube\Models\Pois', function () {
            return new Minube\Models\Pois();
        });
    }
}
