<?php


namespace Minube\Models;

use Phalcon\Mvc\Model;

class Zones extends Model {

	protected $id;
	protected $name;
	protected $country_id;

	public function initialize() {
		$this->belongsTo(
			'country_id',
			Countries::class,
			'id'
		);

		$this->hasMany(
			'id',
			Cities::class,
			'zone_id'
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
	public function getCountryId() {
		return $this->country_id;
	}

	/**
	 * @param mixed $country_id
	 */
	public function setCountryId( $country_id ) {
		$this->country_id = $country_id;
	}


}