<?php
$settings = array(
    'database' => array(
        'adapter' => 'Mysql',
        'host' => '172.18.0.2',
        'username' => 'phalcon',
        'password' => 'secret',
        'name' => 'phalcondb',
        'port' => 3306
    ),
    'redis' => array(
        'adapter' => 'Redis',
        'host' => '172.18.0.3',
        'username' => null,
        'password' => null,
        'port' => 6379
    ),
);
return $settings;
