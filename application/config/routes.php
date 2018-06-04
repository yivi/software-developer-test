<?php

$routes[] = array(
    'method' => 'get',
    'route' => '/status',
    'handler' => array('Minube\Controllers\StatusController', 'statusAction')
);

//$routes[] = array(
//    'method' => 'get',
//    'route' => '/pois/get/{countryId}',
//    'handler' => array('Minube\Controllers\PoisController', 'getAction')
//);

return $routes;
