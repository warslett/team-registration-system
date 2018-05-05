<?php

namespace App\Tests\Service;

use App\Entity\Team;
use App\Entity\Walker;
use App\Service\WalkerReferenceCharacterService;
use App\Tests\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;

class WalkerReferenceCharacterServiceTest extends TestCase
{

    public function testGetNextAvailable_FirstWalker_ReturnsA()
    {
        $team = $this->mockTeam([]);
        $service = new WalkerReferenceCharacterService();

        $result = $service->getNextAvailable($team);

        $this->assertEquals('A', $result);
    }

    public function testGetNextAvailable_AAssigned_ReturnsB()
    {
        $team = $this->mockTeam(['A']);
        $service = new WalkerReferenceCharacterService();

        $result = $service->getNextAvailable($team);

        $this->assertEquals('B', $result);
    }

    public function testGetNextAvailable_AAndCAssigned_ReturnsB()
    {
        $team = $this->mockTeam(['A', 'C']);
        $service = new WalkerReferenceCharacterService();

        $result = $service->getNextAvailable($team);

        $this->assertEquals('B', $result);
    }

    public function testGetNextAvailable_ABAndCAssigned_ReturnsD()
    {
        $team = $this->mockTeam(['A', 'B', 'C']);
        $service = new WalkerReferenceCharacterService();

        $result = $service->getNextAvailable($team);

        $this->assertEquals('D', $result);
    }

    public function testGetNextAvailable_BAndDAssigned_ReturnsA()
    {
        $team = $this->mockTeam(['B', 'D']);
        $service = new WalkerReferenceCharacterService();

        $result = $service->getNextAvailable($team);

        $this->assertEquals('A', $result);
    }

    /**
     * @param array|string[] $assignedReferenceCharacters
     * @return Team
     */
    private function mockTeam(array $assignedReferenceCharacters): Team
    {
        $team = m::mock(Team::class);
        $walkers = [];
        foreach ($assignedReferenceCharacters as $assignedReferenceCharacter) {
            $walkers[] = $this->mockWalker($assignedReferenceCharacter);
        }
        $team->shouldReceive('getWalkers')->andReturn(new ArrayCollection($walkers));
        return $team;
    }

    /**
     * @param string $assignedReferenceCharacter
     * @return Walker|m\MockInterface
     */
    private function mockWalker(string $assignedReferenceCharacter): Walker
    {
        $walker = m::mock(Walker::class);
        $walker->shouldReceive('getReferenceCharacter')->andReturn($assignedReferenceCharacter);
        return $walker;
    }
}
