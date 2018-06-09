<?php

namespace Minube\Models;

use Phalcon\Mvc\Model;

class Countries extends Model {

	protected $id;
	protected $name;

	public function initialize() {
		$this->hasMany(
			'id',
			Zones::class,
			'country_id'
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


}