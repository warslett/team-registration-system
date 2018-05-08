<?php

namespace App\Behat\Context;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class CleanSlateContext implements Context
{

    /**
     * @var ORMPurger
     */
    private $purger;

    /**
     * SetupContext constructor.
     * @param ORMPurger $purger
     */
    public function __construct(ORMPurger $purger)
    {
        $this->purger = $purger;
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->purger->purge();
    }
}