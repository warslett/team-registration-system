Feature: GET Teams Collection
  In order to synchronise the Teams from the system with the hike control manager
  As an API User I need to be able to fetch a list of Teams in a structured format

  Background:
    Given that "the api user" is a User with the following properties:
      | email    | api@example.com |
      | password | development     |
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference       |
      | the api user    |

  Scenario: No Teams
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/teams"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an empty array

  Scenario: One Team
    Given that "the event" is an Event
    And that "the hike" is a Hike for "the event"
    And that "the user" is a User
    And that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/teams"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 1
    And item 0 in the array at JSON node "hydra:member" should contain the following data:
      | Property | Value      |
      | name     | Alpha Team |

  Scenario: Many Teams
    Given that "the event" is an Event
    And that "the hike" is a Hike for "the event"
    And that "the user" is a User
    And that "the first team" is a Team for "the hike" registered by "the user"
    And that "the second team" is a Team for "the hike" registered by "the user"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/teams"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 2