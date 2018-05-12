Feature: GET Team
  In order to synchronise a specific Team from the system with the hike control manager
  As an API User I need to be able to fetch a single Team in a structured format

  Background:
    Given that "the api user" is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference       |
      | the api user    |

  Scenario: Team exists
    Given that "the user" is a User with email "john@acme.co" and password "Password1!"
    And that "the event" is an Event
    And that "the hike" is a Hike for "the event"
    And that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the Team URI for "the team"
    Then the response code from the API response should be 200
    And JSON response should contain the following data:
      | Property | Value      |
      | name     | Alpha Team |
    And the JSON node "hike" is a Hike link to "the hike"

  Scenario: Team does not exist
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/team/1"
    Then the response code from the API response should be 404