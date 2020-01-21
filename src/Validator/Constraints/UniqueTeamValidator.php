<?php

namespace App\Validator\Constraints;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueTeamValidator extends ConstraintValidator
{

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param Team $team
     * @param Constraint $constraint
     */
    public function validate($team, Constraint $constraint)
    {
        if (false === $team instanceof Team) {
            throw new UnexpectedTypeException($team, Team::class);
        }

        if (false === $constraint instanceof UniqueTeam) {
            throw new UnexpectedTypeException($constraint, UniqueTeam::class);
        }

        if (null === $team->getTeamNumber() && null === $team->getStartTime()) {
            return;
        }

        $teamWithNumber = $this->teamRepository->findOneByTeamForHikeWithTeamNumber(
            $team->getHike(),
            $team->getTeamNumber()
        );
        if (null !== $teamWithNumber && $team !== $teamWithNumber) {
            $this->context->buildViolation(sprintf("That team number is taken by \"%s\"", $teamWithNumber->getName()))
                ->atPath('teamNumber')
                ->addViolation()
            ;
        }

        $teamWithStartTime = $this->teamRepository->findOneTeamForHikeWithStartTime(
            $team->getHike(),
            $team->getStartTime()
        );
        if (null !== $teamWithStartTime && $team !== $teamWithStartTime) {
            $this->context->buildViolation(sprintf(
                "That start time is taken by \"%s\"",
                $teamWithStartTime->getName()
            ))
                ->atPath('startTime')
                ->addViolation()
            ;
        }
    }
}
