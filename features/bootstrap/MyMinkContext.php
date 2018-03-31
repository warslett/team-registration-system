<?php

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext as BehatMinkContext;

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
}