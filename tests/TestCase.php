<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery as m;

class TestCase extends PHPUnitTestCase
{

    /**
     * PHPUnit now throws a paddy if no assertions are made. Sometimes we assert using Mockery shouldHaveReceived and
     * shouldNotHaveReceived expectations. Mockery allows us to get the count of this but also includes a count of stubs
     * ie. shouldReceive which are not really assertions so we need to loop through these and subtract it from the total
     * expectation count. We can then add this count to PHPUnit's assertion count and then we have a meaningful
     * assertion count...
     * @throws \Exception
     */
    public function tearDown()
    {
        $mockeryAssertions = m::getContainer()->mockery_getExpectationCount();
        foreach (m::getContainer()->getMocks() as $mock) {
            foreach ($mock->mockery_getExpectations() as $director) {
                $mockeryAssertions -= $director->getExpectationCount();
            }
        }
        $this->addToAssertionCount($mockeryAssertions);
        m::getContainer()->mockery_close();
    }
}
