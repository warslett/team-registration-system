Feature: GET Events Collection
  In order to synchronise the Events from the system with the event control manager
  As an API User I need to be able to fetch a list of Events in a structured format

  Background:
    Given that "the api user" is a User with the following properties:
      | email    | api@example.com |
      | password | development     |
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Reference       |
      | the api user    |

  Scenario: No Events
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an empty array

  Scenario: One Event
    Given that "the event" is an Event with the following properties:
      | name               | Three Towers |
      | date               | 2999-03-31   |
      | registrationOpens  | 2998-09-30   |
      | registrationCloses | 2999-02-28   |
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 1
    And item 0 in the array at JSON node "hydra:member" should contain the following data:
      | Property           | Value                     |
      | name               | Three Towers              |
      | date               | 2999-03-31T00:00:00+00:00 |
      | registrationOpens  | 2998-09-30T00:00:00+00:00 |
      | registrationCloses | 2999-02-28T00:00:00+00:00 |

  Scenario: Many Events
    Given that "the first event" is an Event
    And that "the second event" is an Event
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 2