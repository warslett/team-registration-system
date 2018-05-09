Feature: GET Hikes Collection
  In order to synchronise the Hikes from the system with the hike control manager
  As an API User I need to be able to fetch a list of Hikes in a structured format

  Background:
    Given that "the api user" is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference       |
      | the api user    |

  Scenario: No Hikes
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an empty array

  Scenario: One Hike
    Given that "the event" is an Event
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 1
    And item 0 in the array at JSON node "hydra:member" should contain the following data:
      | Property | Value      |
      | name     | Scout Hike |

  Scenario: Many Hikes
    Given that "the event" is an Event
    And that "the first hike" is a Hike called "Scout Hike" for "the event"
    And that "the second hike" is a Hike called "Explorer Hike" for "the event"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 2