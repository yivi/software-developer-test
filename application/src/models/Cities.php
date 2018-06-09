<?php

namespace Minube\Models;

use Phalcon\Mvc\Model;

class Cities extends Model {

	protected $id;
	protected $country_id;
	protected $zone_id;
	protected $name;
	protected $geocode_id;

	public function initialize() {
		$this->belongsTo(
			'zone_id',
			Zones::class,
			'id'
		);

		$this->hasMany(
			'id',
			Pois::class,
			'city_id'
		);
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getCountryId() {
		return $this->country_id;
	}

	/**
	 * @param mixed $country_id
	 */
	public function setCountryId( $country_id ) {
		$this->country_id = $country_id;
	}

	/**
	 * @return mixed
	 */
	public function getZoneId() {
		return $this->zone_id;
	}

	/**
	 * @param mixed $zone_id
	 */
	public function setZoneId( $zone_id ) {
		$this->zone_id = $zone_id;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getGeocodeId() {
		return $this->geocode_id;
	}

	/**
	 * @param mixed $geocode_id
	 */
	public function setGeocodeId( $geocode_id ) {
		$this->geocode_id = $geocode_id;
	}


}