<?php

namespace Minube\Models;

class Pois extends \Phalcon\Mvc\Model
{
    /**
     * @TODO: Implement all the functionality
     */
    public function findByLocation($countryId)
    {
        $sql = 'SELECT * FROM table WHERE condition = :condition';
        $pois = $this->di->get('db')->query($sql, array('condition' => $countryId))->fetchAll();
        if ($pois) {
            //found - manage the result
        } else {
            //not found
        }
    }
}
