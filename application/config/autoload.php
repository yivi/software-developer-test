<?php

$appDir = dirname(__DIR__);
$srcDir = $appDir . '/src';

$autoload = array(
    'Minube\Application' => $srcDir . '/library/application/',
    'Minube\Interfaces' => $srcDir . '/library/interfaces/',
    'Minube\Controllers' => $srcDir . '/controllers/',
    'Minube\Models' => $srcDir . '/models/',
);
return $autoload;
