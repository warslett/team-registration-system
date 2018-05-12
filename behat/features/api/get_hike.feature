Feature: GET Hike
  In order to synchronise a specific Hike from the system with the hike control manager
  As an API User I need to be able to fetch a single Hike in a structured format

  Background:
    Given that "the api user" is a User with the following properties:
      | email    | api@example.com |
      | password | development     |
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference    |
      | the api user |

  Scenario: Hike exists
    Given that "the event" is an Event
    And that "the hike" is a Hike for "the event" with the following properties:
      | name       | Scout Hike |
      | maxWalkers | 4          |
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the Hike URI for "the hike"
    Then the response code from the API response should be 200
    And JSON response should contain the following data:
      | Property   | Value      |
      | name       | Scout Hike |
      | maxWalkers | 4          |
    And the JSON node "event" is an Event link to "the event"

  Scenario: Hike exists with Teams
    Given that "the user" is a User
    And that "the event" is an Event
    And that "the hike" is a Hike for "the event"
    And that "the team" is a Team for "the hike" registered by "the user"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the Hike URI for "the hike"
    Then the JSON node "teams" is an array containing a Team link to "the team"

  Scenario: Hike does not exist
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/hikes/1"
    Then the response code from the API response should be 404