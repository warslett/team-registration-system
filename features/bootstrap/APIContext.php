<?php

namespace App\Context;

use App\Repository\EventRepository;
use App\Repository\HikeRepository;
use App\Repository\TeamRepository;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
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
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var HikeRepository
     */
    private $hikeRepository;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * APIContext constructor.
     * @param ClientInterface $guzzle
     * @param EventRepository $eventRepository
     * @param HikeRepository $hikeRepository
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        ClientInterface $guzzle,
        EventRepository $eventRepository,
        HikeRepository $hikeRepository,
        TeamRepository $teamRepository
    ) {
        $this->guzzle = $guzzle;
        $this->eventRepository = $eventRepository;
        $this->hikeRepository = $hikeRepository;
        $this->teamRepository = $teamRepository;
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
        Assert::assertEquals($size, count($data[$node]));
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
        $item = $array[$index];
        Assert::assertNotNull($item, sprintf("No item at index %d", $index));
        $this->assertTableDataInAssocArray($table, $item);
    }

    /**
     * @When /^I send a get request to the URI for the Event called "([^"]*)"$/
     */
    public function iSendAGetRequestToTheURIForTheEventCalled($eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        $this->iSendAGetRequestTo(sprintf("/api/events/%d", $event->getId()));
    }

    /**
     * @When /^I send a get request to the URI for the Hike called "([^"]*)" on the Event "([^"]*)"$/
     * @param string $hikeName
     * @param string $eventName
     */
    public function iSendAGetRequestToTheURIForTheHikeCalledOnTheEvent(string $hikeName, string $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        $hike = $this->hikeRepository->findOneByNameAndEvent($hikeName, $event);
        $this->iSendAGetRequestTo(sprintf("/api/hikes/%d", $hike->getId()));
    }

    /**
     * @Given /^I send a get request to the URI for the Team called "([^"]*)" for the Hike "([^"]*)" on the Event "([^"]*)"$/
     */
    public function iSendAGetRequestToTheURIForTheTeamCalledForTheHikeOnTheEvent($teamName, $hikeName, $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        $hike = $this->hikeRepository->findOneByNameAndEvent($hikeName, $event);
        $team = $this->teamRepository->findOneByNameAndHike($teamName, $hike);
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
     * @Then /^the JSON node "([^"]*)" is a link to the Event called "([^"]*)"$/
     */
    public function theJSONNodeIsALinkToTheEventCalled($node, $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        $expected = sprintf("/api/events/%d", $event->getId());
        $data = $this->responseData();
        $actual = $data[$node];
        Assert::assertEquals($expected, $actual);
    }

    /**
     * @Given /^the JSON node "([^"]*)" is a link to the Hike called "([^"]*)" on the Event "([^"]*)"$/
     */
    public function theJSONNodeIsALinkToTheHikeCalledOnTheEvent($node, $hikeName, $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        $hike = $this->hikeRepository->findOneByNameAndEvent($hikeName, $event);
        $expected = sprintf("/api/hikes/%d", $hike->getId());
        $data = $this->responseData();
        $actual = $data[$node];
        Assert::assertEquals($expected, $actual);
    }

    /**
     * @param TableNode $table
     * @param array $item
     */
    private function assertTableDataInAssocArray(TableNode $table, array $item): void
    {
        foreach ($table->getColumnsHash() as $property) {
            Assert::assertEquals($property['Value'], $item[$property['Key']]);
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