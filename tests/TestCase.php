<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery as m;

class TestCase extends PHPUnitTestCase
{

    public function tearDown()
    {
        $this->addToAssertionCount(
            m::getContainer()->mockery_getExpectationCount()
        );
    }
}
