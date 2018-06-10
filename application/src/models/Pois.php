<?php

namespace Minube\Models;

use Phalcon\Mvc\Model;

class Pois extends Model {

	protected $id;
	protected $city_id;
	protected $subcategory_id;
	protected $name;
	protected $geocode_id;

	public function initialize() {
		$this->useDynamicUpdate( true );
		$this->belongsTo(
			'city_id',
			Cities::class,
			'id'
		);
	}

	/**
	 * @param string $location
	 * @param int    $locationId
	 * @param int    $pageSize
	 * @param int    $pageNumber
	 * @param int    $purgeCache
	 *
	 * @return \Phalcon\Mvc\Model\ResultsetInterface
	 * @throws \Exception
	 */
	public static function findByLocation( $location, $locationId, $pageNumber = 1, $pageSize = 100, $purgeCache = 0 ) {

		$offset = $pageSize * ( $pageNumber - 1 );
		if ( ! in_array( $location, [ 'city', 'zone', 'country' ] ) ) {
			throw new \Exception( "Invalid location type received ($location). " );
		}

		if ( $purgeCache ) {
			// TODO: Purge cache if $purgeCache is != 0
		}

		$query = self::query()
		             ->orderBy( 'Minube\Models\Pois.id ASC' )
		             ->columns( [ 'Minube\Models\Pois.id', 'Minube\Models\Pois.name', 'Minube\Models\Pois.geocode_id' ] )
		             ->cache( [ 'key' => "poisfindbylocation-$location-$locationId-$pageNumber-$pageSize", 'lifetime' => 600 ] )
		             ->limit( 20, $offset );


		switch ( $location ):
			case "country":
				$query
					->join( Countries::class )
					->join( Zones::class )
					->join( Cities::class )
					->where( 'Minube\Models\Countries.id = :locationId:' );

				break;

			case "zone":
				$query
					->join( Zones::class )
					->join( Cities::class )
					->where( 'Minube\Models\Zones.id = :locationId:' );
				break;

			case "city":
				$query
					->where( 'city_id = :locationId:' );
				break;

		endswitch;

		$query->bind( [ 'locationId' => $locationId ] );

		return $query->execute();

	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getCityId() {
		return $this->city_id;
	}

	/**
	 * @param int $city_id
	 */
	public function setCityId( $city_id ) {
		$this->city_id = $city_id;
	}

	/**
	 * @return int
	 */
	public function getSubcategoryId() {
		return $this->subcategory_id;
	}

	/**
	 * @param int $subcategory_id
	 */
	public function setSubcategoryId( $subcategory_id ) {
		$this->subcategory_id = $subcategory_id;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getGeocodeId() {
		return $this->geocode_id;
	}

	/**
	 * @param int $geocode_id
	 */
	public function setGeocodeId( $geocode_id ) {
		$this->geocode_id = $geocode_id;
	}


}
