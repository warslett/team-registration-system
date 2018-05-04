Feature: GET Event
  In order to synchronise a specific Event from the system with the event control manager
  As an API User I need to be able to fetch a single Event in a structured format

  Background:
    Given that "the api user" is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference       |
      | the api user    |

  Scenario: Event exists
    Given that "the event" is an Event called "Three Towers" taking place on "31st March 2999"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the Event URI for "the event"
    Then the response code from the API response should be 200
    And JSON response should contain the following data:
      | Key  | Value                     |
      | name | Three Towers              |
      | date | 2999-03-31T00:00:00+00:00 |

  Scenario: Event exists with Hikes
    Given that "the event" is an Event called "Three Towers" taking place on "31st March 2999"
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to the Event URI for "the event"
    Then the JSON node "hikes" is an array containing a Hike link to "the hike"

  Scenario: Event does not exist
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events/1"
    Then the response code from the API response should be 404