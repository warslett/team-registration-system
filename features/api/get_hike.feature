Feature: GET Hike
  In order to synchronise a specific Hike from the system with the hike control manager
  As an API User I need to be able to fetch a single Hike in a structured format

  Background:
    Given there is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Email           |
      | api@example.com |

  Scenario: Hike exists
    Given that there is an Event called "Three Towers" taking place at "31st March 2999"
    And that there is a Hike called "Scout Hike" for the Event "Three Towers"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the URI for the Hike called "Scout Hike" on the Event "Three Towers"
    Then the response code from the API response should be 200
    And JSON response should contain the following data:
      | Key  | Value      |
      | name | Scout Hike |
    And the JSON node "event" is a link to the Event called "Three Towers"

  Scenario: Hike does not exist
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes/1"
    Then the response code from the API response should be 404