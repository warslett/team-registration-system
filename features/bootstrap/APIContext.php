<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\ClientInterface;
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

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
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
        $this->response = $this->guzzle->request('GET', 'http://nginx' . $uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
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

    /**
     * @Given /^the JSON node "([^"]*)" from the API response should be an array of size (\d+)$/
     */
    public function theJSONNodeFromTheAPIResponseShouldBeAnArrayOfSize($node, $size)
    {
        $data = $this->responseData();
        Assert::assertEquals($size, count($data[$node]));
    }

    /**
     * @Given /^item (\d+) in the array at JSON node "([^"]*)" should contain the following data:$/
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
        foreach ($table->getColumnsHash() as $property) {
            Assert::assertEquals($property['Value'], $item[$property['Key']]);
        }
    }
}