<?php

return [
	// default status route
	[
		'method'  => 'get',
		'route'   => '/status',
		'handler' => [ 'Minube\Controllers\StatusController', 'statusAction' ],
	],
	// get POIs by location (location type and id are passed by query string)
	[
		'method'  => 'get',
		'route'   => '/pois',
		'handler' => [ \Minube\Controllers\PoisController::class, 'getByLocationAction' ],
	],
	[
		'method'  => 'put',
		'route'   => '/pois/{poisId}',
		'handler' => [ \Minube\Controllers\PoisController::class, 'updateAction' ],
	],
];
