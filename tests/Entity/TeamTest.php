<?php

namespace App\Tests\Entity;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\Walker;
use App\Tests\TestCase;
use Mockery as m;

class TeamTest extends TestCase
{

    public function testHasMaxWalkers_MaxWalkersAdded_ReturnsTrue()
    {
        $maxWalkers = 3;
        $hike = $this->mockHike(['MaxWalkers' => $maxWalkers]);
        $team = new Team();
        $team->setHike($hike);
        for ($i = 0; $i < $maxWalkers; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }

        $result = $team->hasMaxWalkers();

        $this->assertTrue($result);
    }

    public function testHasMaxWalkers_MaxWalkersNotAdded_ReturnsFalse()
    {
        $hike = $this->mockHike(['MaxWalkers' => 4]);
        $team = new Team();
        $team->setHike($hike);
        for ($i = 0; $i < 3; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }

        $result = $team->hasMaxWalkers();

        $this->assertFalse($result);
    }
    public function testHasEnoughWalkers_MinWalkersAdded_ReturnsTrue()
    {
        $minWalkers = 3;
        $hike = $this->mockHike(['MinWalkers' => $minWalkers]);
        $team = new Team();
        $team->setHike($hike);
        for ($i = 0; $i < $minWalkers; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }

        $result = $team->hasEnoughWalkers();

        $this->assertTrue($result);
    }

    public function testHasEnoughWalkers_MinWalkersNotAdded_ReturnsFalse()
    {
        $hike = $this->mockHike(['MinWalkers' => 3]);
        $team = new Team();
        $team->setHike($hike);
        for ($i = 0; $i < 2; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }

        $result = $team->hasEnoughWalkers();

        $this->assertFalse($result);
    }

    public function testGetFeesDue_ReturnsFeePerWalkerTimesWalkers()
    {
        $hike = $this->mockHike(['FeePerWalker' => 12.0]);
        $team = new Team();
        $team->setHike($hike);
        for ($i = 0; $i < 3; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }

        $result = $team->getFeesDue();

        $this->assertEquals(36.0, $result);
    }

    /**
     * @param array $properties
     * @return Hike|m\Mock
     */
    private function mockHike(array $properties = []): Hike
    {
        $hike = m::mock(Hike::class);
        foreach ($properties as $key => $value) {
            $hike->shouldReceive('get' . $key)->andReturn($value);
        }
        return $hike;
    }

    /**
     * @return Walker|m\Mock
     */
    private function mockWalker(): Walker
    {
        $walker = m::mock(Walker::class);
        return $walker;
    }
}
