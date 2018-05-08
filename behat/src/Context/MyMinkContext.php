<?php

namespace App\Behat\Context;

use App\Entity\Team;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext as BehatMinkContext;
use PHPUnit\Framework\Assert;

class MyMinkContext extends BehatMinkContext
{

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(FixtureStorageService $fixtureStorage)
    {
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Then there is an alert with the message :message
     * @throws \Exception
     */
    public function thereIsAnAlertWithTheMessage(string $message)
    {
        $page = $this->getSession()->getPage();
        $locator = sprintf("//*[contains(@class, 'alert') and contains(text(), '%s')]", $message);
        if (!$page->has('xpath', $locator)) {
            $message = "Could not find the following alert\n$message\n";
            $alerts = $page->findAll('css', '.alert');
            if (count($alerts) > 0) {
                $message .= "The following alerts were found:\n";
                foreach ($alerts as $alert) {
                    /** @var NodeElement $alert */
                    $message .= trim($alert->getHtml()) . "\n";
                }
            }
            throw new \Exception($message);
        }
    }

    /**
     * @When /^I log in with email "([^"]*)" and password "([^"]*)"$/
     */
    public function thatIHaveLoggedInWithEmailAndPassword($email, $password)
    {
        $this->visit('/user/login');
        $this->fillField('Email', $email);
        $this->fillField('Password', $password);
        $this->pressButton('Log in');
    }

    /**
     * @Given /^I print the page title$/
     */
    public function iPrintThePageTitle()
    {
        print($this->getSession()->getPage()->find('css', 'title')->getText());
    }

    /**
     * @Given /^I print the page source$/
     */
    public function iPrintThePageSource()
    {
        print($this->getSession()->getPage()->getOuterHtml());
    }

    /**
     * @Then /^the title should be "([^"]*)"$/
     * @param $title
     */
    public function theTitleShouldBe($title)
    {
        Assert::assertEquals($title, $this->getSession()->getPage()->find('css', 'title')->getText());
    }

    /**
     * @When /^I go to the Team page for "([^"]*)"$/
     * @throws \Exception
     */
    public function iGoToTheTeamPageFor($teamReference)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        $this->visit(sprintf('/teams/%d', $team->getId()));
    }

    /**
     * @When /^I go to the Create Walker page for "([^"]*)"$/
     */
    public function iGoToTheCreateWalkerPageFor($teamReference)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        $this->visit(sprintf('/teams/%d/walkers/create', $team->getId()));
    }

    /**
     * @Then /^the drop down "([^"]*)" includes the option "([^"]*)"$/
     * @param string $dropDownLocator
     * @param string $optionLocator
     */
    public function theDropdownIncludesTheOption(string $dropDownLocator, string $optionLocator)
    {
        $option = $this->findDropDownOption($dropDownLocator, $optionLocator);
        Assert::assertNotNull($option);
    }

    /**
     * @Then /^the drop down "([^"]*)" does not include the option "([^"]*)"$/
     * @param string $dropDownLocator
     * @param string $optionLocator
     */
    public function theDropdownDoesNotIncludeTheOption(string $dropDownLocator, string $optionLocator)
    {
        $option = $this->findDropDownOption($dropDownLocator, $optionLocator);
        Assert::assertNull($option);
    }

    /**
     * @param string $dropDownLocator
     * @param string $optionLocator
     * @return NodeElement|mixed|null
     */
    private function findDropDownOption(string $dropDownLocator, string $optionLocator)
    {
        $dropDown = $this->getSession()->getPage()->findField($dropDownLocator);
        $option = $dropDown->find('named', ['option', $optionLocator]);
        return $option;
    }

    /**
     * @Then /^the following Walkers are listed on the page:$/
     */
    public function theFollowingWalkersAreListedOnThePage(TableNode $table)
    {
        $walkerTable = $this->getSession()->getPage()->find('css', '#walkers');
        $walkerRows = $walkerTable->findAll('css', '.walkers-table__walker');
        $expectedRows = $table->getColumnsHash();
        Assert::assertEquals(
            count($expectedRows),
            count($walkerRows),
            "Failed asserting that expected and actual num Walkers the same length"
        );
        foreach ($expectedRows as $key => $row) {
            /** @var NodeElement $walkerRow */
            $walkerRow = $walkerRows[$key];
            $nameCell = $walkerRow->find('css', '.walkers-table__name');
            Assert::assertEquals($row['Name'], $nameCell->getText());
        }
    }
}
