<?php

namespace App\Behat\Context;

use App\Entity\Event;
use App\Entity\Hike;
use App\Entity\Team;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;

class APIContext implements Context
{

    /**
     * @var ClientInterface
     */
    private $guzzle;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * APIContext constructor.
     * @param ClientInterface $guzzle
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(
        ClientInterface $guzzle,
        FixtureStorageService $fixtureStorage
    ) {
        $this->guzzle = $guzzle;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @When /^I authenticate with the api using email "([^"]*)" and password "([^"]*)"$/
     */
    public function iAuthenticateWithTheApiUsingEmailAndPassword($email, $password)
    {
        $this->response = $this->guzzle->request('POST', 'http://nginx/api/login', [
            'form_params' => [
                '_username' => $email,
                '_password' => $password
            ]
        ]);
        $data = $this->responseData();
        $this->token = $data['token'];
    }

    /**
     * @Given /^I send a get request to "([^"]*)"$/
     */
    public function iSendAGetRequestTo($uri)
    {
        try {
            $this->response = $this->guzzle->request('GET', 'http://nginx' . $uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
        } catch (ClientException $exception) {
            $this->response = $exception->getResponse();
        }
    }

    /**
     * @Then /^the JSON node "([^"]*)" from the API response should be an empty array$/
     * @param string $node
     */
    public function theJSONNodeShouldBeAnEmptyArray(string $node)
    {
        $data = $this->responseData();
        Assert::assertEmpty($data[$node]);
    }

    /**
     * @Then /^the response code from the API response should be (\d+)$/
     */
    public function theResponseCodeFromTheAPIResponseShouldBe($statusCode)
    {
        Assert::assertEquals($statusCode, $this->responseStatusCode());
    }

    /**
     * @Given /^the JSON node "([^"]*)" is included in the response from the API$/
     */
    public function theJSONNodeIsIncludedInTheResponseFromTheAPI($node)
    {
        Assert::assertArrayHasKey($node, $this->responseData());
    }

    /**
     * @Given /^the JSON node "([^"]*)" from the API response should be an array of size (\d+)$/
     */
    public function theJSONNodeFromTheAPIResponseShouldBeAnArrayOfSize($node, $size)
    {
        $data = $this->responseData();
        $array = $data[$node];
        Assert::assertNotNull($array, sprintf("No node with key %s found in response", $node));

        Assert::assertEquals($size, count($array));
    }

    /**
     * @Then /^item (\d+) in the array at JSON node "([^"]*)" should contain the following data:$/
     * @param $index
     * @param $node
     * @param TableNode $table
     */
    public function itemInTheArrayAtJSONNodeShouldContainTheFollowingData($index, $node, TableNode $table)
    {
        $data = $this->responseData();
        $array = $data[$node];
        Assert::assertNotNull($array, sprintf("No node with key %s found in response", $node));

        $item = $array[$index];

        Assert::assertNotNull($item, sprintf("No item at index %d", $index));
        $this->assertTableDataInAssocArray($table, $item);
    }

    /**
     * @When /^I send a get request to the Event URI for "([^"]*)"$/
     * @param $eventReference
     * @throws \Exception
     */
    public function iSendAGetRequestToTheEventURIFor($eventReference)
    {
        $event = $this->fixtureStorage->get(Event::class, $eventReference);
        $this->iSendAGetRequestTo(sprintf("/api/events/%d", $event->getId()));
    }

    /**
     * @When /^I send a get request to the Hike URI for "([^"]*)"$/
     * @param string $hikeReference
     * @throws \Exception
     */
    public function iSendAGetRequestToTheHikeURIFor(string $hikeReference)
    {
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);
        $this->iSendAGetRequestTo(sprintf("/api/hikes/%d", $hike->getId()));
    }

    /**
     * @When /^I send a get request to the Team URI for "([^"]*)"$/
     * @throws \Exception
     */
    public function iSendAGetRequestToTheTeamURIFor($teamReference)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        $this->iSendAGetRequestTo(sprintf("/api/teams/%d", $team->getId()));
    }

    /**
     * @Then /^JSON response should contain the following data:$/
     */
    public function jsonResponseShouldContainTheFollowingData(TableNode $table)
    {
        $this->assertTableDataInAssocArray($table, $this->responseData());
    }

    /**
     * @Then /^the JSON node "([^"]*)" is an Event link to "([^"]*)"$/
     * @throws \Exception
     */
    public function theJSONNodeIsAnEventLinkTo($node, $eventReference)
    {
        $event = $this->fixtureStorage->get(Event::class, $eventReference);

        $expected = sprintf("/api/events/%d", $event->getId());
        $data = $this->responseData();
        $actual = $data[$node];
        Assert::assertNotNull($actual, sprintf("No node with key %s found in response", $node));

        Assert::assertEquals($expected, $actual);
    }

    /**
     * @Then /^the JSON node "([^"]*)" is a Hike link to "([^"]*)"$/
     * @throws \Exception
     */
    public function theJSONNodeIsAHikeLinkTo($node, $hikeReference)
    {
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);

        $data = $this->responseData();
        $actual = $data[$node];
        Assert::assertNotNull($actual, sprintf("No node with key %s found in response", $node));

        $expected = sprintf("/api/hikes/%d", $hike->getId());
        Assert::assertEquals($expected, $actual);
    }

    /**
     * @Then /^the JSON node "([^"]*)" is an array containing a Team link to "([^"]*)"$/
     * @throws \Exception
     */
    public function theJSONNodeIsAnArrayContainingATeamLinkTo(string $node, string $teamReference)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);

        $data = $this->responseData();
        $array = $data[$node];
        Assert::assertNotNull($array, sprintf("No node with key %s found in response", $node));

        $expected = sprintf("/api/teams/%d", $team->getId());
        Assert::assertContains($expected, $array);
    }

    /**
     * @Then /^the JSON node "([^"]*)" is an array containing a Hike link to "([^"]*)"$/
     * @throws \Exception
     */
    public function theJSONNodeIsAnArrayContainingAHikeLinkTo(string $node, string $hikeReference)
    {
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);
        $data = $this->responseData();
        $array = $data[$node];
        Assert::assertNotNull($array, sprintf("No node with key %s found in response", $node));

        $expected = sprintf("/api/hikes/%d", $hike->getId());
        Assert::assertContains($expected, $array);
    }

    /**
     * @param TableNode $table
     * @param array $item
     */
    private function assertTableDataInAssocArray(TableNode $table, array $item): void
    {
        foreach ($table->getColumnsHash() as $property) {
            Assert::assertEquals($property['Value'], $item[$property['Property']]);
        }
    }

    /**
     * @return array
     */
    private function responseData(): array
    {
        $this->response->getBody()->rewind();
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @return int
     */
    private function responseStatusCode(): int
    {
        return $this->response->getStatusCode();
    }
}
