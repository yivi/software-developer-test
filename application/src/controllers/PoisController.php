<?php

namespace Minube\Controllers;

use Minube\Models\Pois;

class PoisController extends \Phalcon\Mvc\Controller {

	/** @var Pois */
	private $poisService;

	/**
	 * @param $cityId
	 *
	 * @return string
	 */
	public function getByLocationAction() {

		$cityId     = $this->request->getQuery( 'city', 'int' );
		$zoneId     = $this->request->getQuery( 'zone', 'int' );
		$countryId  = $this->request->getQuery( 'country', 'int' );
		$page       = $this->request->getQuery( 'page', 'int', 1 );
		$pageSize   = $this->request->getQuery( 'pageSize', 'int', 100 );
		$purgeCache = $this->request->getQuery( 'purgeCache', 'int', 0 );

		$this->response->setContentType( 'application/json' );

		// we haven't received any location id. exit.
		$parameterCheck = $this->checkParameters( $cityId, $zoneId, $countryId );
		if ( $parameterCheck !== 'OK' ) {
			return json_encode( [
				'status'  => 'KO',
				'message' => $parameterCheck,
				'data'    => [],
			] );
		}

		switch ( true ):
			case $cityId:
				$location   = 'city';
				$locationId = $cityId;
				break;
			case $zoneId:
				$location   = 'zone';
				$locationId = $zoneId;
				break;
			case $countryId:
				$location   = 'country';
				$locationId = $countryId;
				break;
			default:
				$location   = false;
				$locationId = false;
		endswitch;

		try {
			$pois = $this->getPoisService()->findByLocation( $location, $locationId, $page, $pageSize, $purgeCache );
		} catch ( \Exception $exception ) {
			return json_encode( [
				'status'  => 'KO',
				'message' => $exception->getMessage(),
				'data'    => [],
			] );
		}

		return json_encode( [
			'status'   => 'OK',
			'data'     => $pois,
			'page'     => $page,
			'pageSize' => $pageSize,
		] );

	}

	public function updateAction( $poisId ) {

		/** @var Pois $pois */
		$this->response->setContentType( 'application/json' );
		$poisId = filter_var( $poisId );
		$pois   = Pois::findFirst( [ "id = $poisId" ] );

		// If we couldn't find the POI by this ID, nothing to update.
		if ( ! $pois ) {
			$this->response->setStatusCode( 404 );

			return json_encode(
				[
					'status'  => 'KO',
					'message' => 'That POI does\'t exist. Cannot update it.',
				]
			);
		}

		// get and decode the json body
		$request = $this->request->getJsonRawBody( true );

		// if the result is not an associative array, something went wrong.
		if ( ! is_array( $request ) ) {
			return [
				'status'  => 'KO',
				'message' => 'Malformed request.',
			];
		}

		// check for the corresponding keys in the user input, and set the corresponding property
		if ( array_key_exists( 'city_id', $request ) ) {
			$pois->setCityId( filter_var( $request['city_id'] ) );
		}

		if ( array_key_exists( 'subcategory_id', $request ) ) {
			$pois->setSubcategoryId( filter_var( $request['subcategory_id'] ) );
		}

		if ( array_key_exists( 'name', $request ) ) {
			$pois->setName( $request['name'] );
		}

		if ( array_key_exists( 'geocode_id', $request ) ) {
			$pois->setGeocodeId( filter_var( $request['geocode_id'] ) );
		}

		// if the object isn't dirty after this, let's exit without further ado.
		if ( ! $pois->getDirtyState() ) {
			return json_encode(
				[
					'status'  => 'OK',
					'message' => "Nothing to update.",
				]
			);
		}

		$success = $pois->update();

		if ( $success === true ) {
			return json_encode(
				[
					'status'  => 'OK',
					'message' => "POI $poisId updated successfully",
				]
			);

		} else {
			return json_encode( [
				'status'  => 'KO',
				'message' => "Something went wrong. Update didn't take.",
			] );
		}

	}

	/**
	 * @param int $cityId
	 * @param int $zoneId
	 * @param int $countryId
	 *
	 * @return string
	 */
	private function checkParameters( $cityId, $zoneId, $countryId ) {

		if ( ! $cityId && ! $zoneId && ! $countryId ) {
			return 'Missing City, Zone or Country Id. Can\'t find POIs without a location ID';
		}

		// we have received too many location IDs, which may lead to inconsistent results. exit.
		if ( $cityId && $zoneId || $cityId && $countryId || $zoneId && $countryId ) {
			return 'Received more than one location ID. Please, provide only with City, Zone or Country ID.';
		}

		return 'OK';
	}

	/**
	 * @return Pois
	 */
	public function getPoisService(): Pois {
		// This is is very hackish, but since we do not have real depencency injection in Phalcon, mocking
		// poisService is otherwise difficult
		if ( $this->poisService === null ) {
			$this->poisService = $this->getDI()->get( Pois::class );
		}

		return $this->poisService;
	}

	/**
	 * @param Pois $poisService
	 */
	public function setPoisService( Pois $poisService ) {
		$this->poisService = $poisService;
	}

}
