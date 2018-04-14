Feature: GET Hikes Collection
  In order to synchronise the Hikes from the system with the hike control manager
  As an API User I need to be able to fetch a list of Hikes in a structured format

  Background:
    Given that there is an Event called "Three Towers" taking place "+6 months" from now
    And there is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Email           |
      | api@example.com |

  Scenario: No Hikes
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an empty array

  Scenario: One Hike
    Given that there is a Hike called "Scout Hike" for the Event "Three Towers"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 1
    And item 0 in the array at JSON node "hydra:member" should contain the following data:
      | Key  | Value      |
      | name | Scout Hike |

  Scenario: Many Hikes
    Given that there is a Hike called "Scout Hike" for the Event "Three Towers"
    And that there is a Hike called "Explorer Hike" for the Event "Three Towers"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 2