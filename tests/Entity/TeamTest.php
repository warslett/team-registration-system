<?php

namespace App\Tests\Entity;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\TeamPayment;
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
        $this->addMockWalkersToTeam($maxWalkers, $team);

        $result = $team->hasMaxWalkers();

        $this->assertTrue($result);
    }

    public function testHasMaxWalkers_MaxWalkersNotAdded_ReturnsFalse()
    {
        $hike = $this->mockHike(['MaxWalkers' => 4]);
        $team = new Team();
        $team->setHike($hike);
        $this->addMockWalkersToTeam(3, $team);

        $result = $team->hasMaxWalkers();

        $this->assertFalse($result);
    }
    public function testHasEnoughWalkers_MinWalkersAdded_ReturnsTrue()
    {
        $minWalkers = 3;
        $hike = $this->mockHike(['MinWalkers' => $minWalkers]);
        $team = new Team();
        $team->setHike($hike);
        $this->addMockWalkersToTeam($minWalkers, $team);

        $result = $team->hasEnoughWalkers();

        $this->assertTrue($result);
    }

    public function testHasEnoughWalkers_MinWalkersNotAdded_ReturnsFalse()
    {
        $hike = $this->mockHike(['MinWalkers' => 3]);
        $team = new Team();
        $team->setHike($hike);
        $this->addMockWalkersToTeam(2, $team);

        $result = $team->hasEnoughWalkers();

        $this->assertFalse($result);
    }

    public function testGetFeesDue_ReturnsFeePerWalkerTimesWalkers()
    {
        $hike = $this->mockHike(['FeePerWalker' => 12.0]);
        $team = new Team();
        $team->setHike($hike);
        $this->addMockWalkersToTeam(3, $team);

        $result = $team->getFeesDue();

        $this->assertEquals(36.0, $result);
    }

    /**
     * @dataProvider feesDueDataProvider
     * @param int $walkers
     * @param float $feePerWalker
     * @param array $payments
     * @param bool $hasDueFees
     * @param float $feesDue
     */
    public function testGetFeesDue(int $walkers, float $feePerWalker, array $payments, bool $hasDueFees, float $feesDue)
    {
        $team = new Team();
        $this->addMockPaymentsToTeam($payments, $team);
        $this->addMockWalkersToTeam($walkers, $team);
        $team->setHike($this->mockHike(['FeePerWalker' => $feePerWalker]));

        $this->assertEquals($hasDueFees, $team->hasDueFees());
        $this->assertEquals($feesDue, $team->getFeesDue());
    }

    public function feesDueDataProvider()
    {
        return[
            [0, 0.0 , [], false, 0.0],
            [3, 12.0 , [], true, 36.0],
            [3, 12.0 , [['total' => 3600, 'completed' => true]], false, 0.0],
            [3, 12.0 , [['total' => 3600, 'completed' => false]], true, 36.0],
            [4, 12.0 , [['total' => 3600, 'completed' => true]], true, 12.0],
            [4, 12.0 , [['total' => 3600, 'completed' => true], ['total' => 1200, 'completed' => true]], false, 0.0],
            [3, 12.0 , [['total' => 3600, 'completed' => true], ['total' => 1200, 'completed' => true]], false, -12.0],
        ];
    }

    /**
     * @dataProvider feesPaidDataProvider
     * @param array $payments
     * @param float $feesPaid
     */
    public function testGetFeesPaid(array $payments, float $feesPaid)
    {
        $team = new Team();
        $this->addMockPaymentsToTeam($payments, $team);

        $this->assertEquals($feesPaid, $team->getFeesPaid());
    }

    public function feesPaidDataProvider()
    {
        return [
            [[], 0.0],
            [[['total' => 3600, 'completed' => false]], 0.0],
            [[['total' => 3600, 'completed' => true]], 36.0],
            [[['total' => 1200, 'completed' => true],['total' => 2400, 'completed' => false]], 12.0],
        ];
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

    /**
     * @param array $payments
     * @param $team
     */
    public function addMockPaymentsToTeam(array $payments, Team $team): void
    {
        foreach ($payments as $data) {
            $payment = m::mock(TeamPayment::class);
            $payment->shouldReceive('getTotalAmount')->andReturn($data['total']);
            $payment->shouldReceive('isCompleted')->andReturn($data['completed']);
            $team->getPayments()->add($payment);
        }
    }

    /**
     * @param int $walkers
     * @param Team $team
     */
    public function addMockWalkersToTeam(int $walkers, Team $team): void
    {
        for ($i = 0; $i < $walkers; $i++) {
            $team->getWalkers()->add($this->mockWalker());
        }
    }
}
