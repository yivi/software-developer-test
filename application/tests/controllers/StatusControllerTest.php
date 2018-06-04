<?php

namespace MinubeTests\Controllers;

use Minube\Controllers\StatusController;

class StatusControllerTest extends \UnitTestCase
{

    public function testPingAction()
    {
        $this->expectOutputString('OK');

        $controller = new StatusController();
        $controller->statusAction();
    }
}
