<?php


namespace App\Service;

use App\Entity\Team;

class WalkerReferenceCharacterService
{

    const CHARACTERS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

    /**
     * @param Team $team
     * @return string
     * @throws \Exception
     */
    public function getNextAvailable(Team $team): string
    {
        foreach (self::CHARACTERS as $character) {
            $alreadyAssigned = false;
            foreach ($team->getWalkers() as $walker) {
                if ($walker->getReferenceCharacter() == $character) {
                    $alreadyAssigned = true;
                    continue;
                }
            }
            if (!$alreadyAssigned) {
                return $character;
            }
        }
        throw new \Exception('Unable to assign character reference');
    }
}
