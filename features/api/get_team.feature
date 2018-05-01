Feature: GET Team
  In order to synchronise a specific Team from the system with the hike control manager
  As an API User I need to be able to fetch a single Team in a structured format

  Background:
    Given there is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Email           |
      | api@example.com |

  Scenario: Team exists
    Given there is a User with email "john@acme.co" and password "Password1!"
    And that there is an Event called "Three Towers" taking place at "31st March 2999"
    And that there is a Hike called "Scout Hike" for the Event "Three Towers"
    And that there is a Team called "Alpha Team" for the Hike "Scout Hike" on the Event "Three Towers" registered by "john@acme.co"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the URI for the Team called "Alpha Team" for the Hike "Scout Hike" on the Event "Three Towers"
    Then the response code from the API response should be 200
    And JSON response should contain the following data:
      | Key  | Value      |
      | name | Alpha Team |
    And the JSON node "hike" is a link to the Hike called "Scout Hike" on the Event "Three Towers"

  Scenario: Team does not exist
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/team/1"
    Then the response code from the API response should be 404