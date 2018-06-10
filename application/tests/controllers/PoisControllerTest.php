<?php

namespace MinubeTests\Controllers;

use Minube\Controllers\PoisController;
use Minube\Models\Pois;
use MinubeTests\UnitTestCase;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Manager;

class PoisControllerTest extends UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->di->set('modelsManager', new Manager());
	}

	public function testNotEnoughParameters() {

		$poisController           = new PoisController();
		$poisController->request  = new Request();
		$poisController->response = new Response();


		$result = $poisController->getByLocationAction();

		// since _GET is empty, this should result in a KO result
		$this->assertEquals( 'KO', json_decode( $result, true )['status'] );

	}

	public function testTooManyParameters() {

		$poisController  = new PoisController();
		$mockRequest     = $this->getMock( Request::class );
		$mockPoisService = $this->getMock( Pois::class );

		// passing city, zone and country. this should fail.
		$valueMap = [
			[ 'city', 'int', null, null, null, 1 ],
			[ 'zone', 'int', null, null, null, 1 ],
			[ 'country', 'int', null, null, null, 1 ],
			[ 'page', 'int', 1, null, null, 1 ],
			[ 'pageSize', 'int', 100, null, null, 100 ],
			[ 'purgeCache', 'int', 0, null, null, 0 ],
		];

		$mockRequest
			->expects( $this->exactly( 6 ) )
			->method( 'getQuery' )
			->will( $this->returnValueMap( $valueMap ) );

		$mockPoisService->expects($this->atMost(1))
			->method('findByLocation')
			->will($this->returnValue([]));

		/** @noinspection PhpParamsInspection */
		$poisController->setPoisService($mockPoisService);
		$poisController->request  = $mockRequest;
		$poisController->response = new Response();

		$result = $poisController->getByLocationAction();

		// since we have too many location parameters, this should result in a KO result
		$this->assertEquals( 'KO', json_decode( $result, true )['status'] );

	}

}