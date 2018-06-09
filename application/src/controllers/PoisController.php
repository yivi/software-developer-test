<?php

namespace Minube\Controllers;

use Minube\Models\Pois;

class PoisController extends \Phalcon\Mvc\Controller {

	/**
	 * @param $cityId
	 *
	 * @return string
	 * @throws \Exception
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
		if ( ! $cityId && ! $zoneId && ! $countryId ) {
			return json_encode( [
				'status'  => 'KO',
				'message' => 'Missing City, Zone or Country Id. Can\'t find POIs without a location ID',
				'data'    => [],
			] );
		}

		// we have received too many location IDs, which may lead to inconsistent results. exit.
		if ( $cityId && $zoneId || $cityId && $countryId || $zoneId && $countryId ) {
			return json_encode( [
				'status'  => 'KO',
				'message' => 'Received more than one location ID. Please, provide only with City, Zone or Country ID.',
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

		$pois = Pois::findByLocation( $location, $locationId, $page, $pageSize, $purgeCache );

		return json_encode( [
			'status'   => 'OK',
			'data'     => $pois,
			'page'     => $page,
			'pageSize' => $pageSize,
		] );

	}
}
