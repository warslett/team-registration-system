<?php

namespace App\Context;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext as BehatMinkContext;
use PHPUnit\Framework\Assert;

class MyMinkContext extends BehatMinkContext
{

    /**
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($expectedEmail)
    {
        $actualEmail = $this->findElement('#current-user')->getText();

        if ($expectedEmail !== $actualEmail) {
            throw new \Exception("Expected $expectedEmail got $actualEmail");
        };
    }

    /**
     * @Given there is an alert with the message :message
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
     * @param string $css
     * @return NodeElement
     * @throws Exception
     */
    private function findElement(string $css): NodeElement
    {
        $page = $this->getSession()->getPage();
        $element = $page->find('css', $css);
        if (is_null($element)) {
            throw new \Exception("The element with the selector $css could not be found");
        }
        return $element;
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
}