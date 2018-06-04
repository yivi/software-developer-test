<?php

namespace Minube\Controllers;

class PoisController extends \Phalcon\Mvc\Controller
{
    /**
     * @param integer $countryId
     */
    public function getAction($countryId)
    {
        echo "test (countryId: $countryId)";

        /**
         * @TODO: Implement this. Take into account the performance.
         * Next comments are just hints
         */
        //$data = $this->di->get('\Minube\Models\Pois')->findByLocation($countryId);
        //$this->di->get('redis')->save('key', 'hello world');
        //$data = $this->di->get('redis')->get('key');
    }
}
