Feature: GET Events Collection
  In order to synchronise the Events from the system with the event control manager
  As an API User I need to be able to fetch a list of Events in a structured format

  Background:
    Given there is a User with email "api@example.com" and password "development"
    And that there is a User Group with the role "ROLE_API_USER" with the following members:
      | Email           |
      | api@example.com |

  Scenario: No Events
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an empty array

  Scenario: One Event
    Given that there is an Event called "Three Towers" taking place at "31st March 2999"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 1
    And item 0 in the array at JSON node "hydra:member" should contain the following data:
      | Key  | Value                     |
      | name | Three Towers              |
      | date | 2999-03-31T00:00:00+00:00 |

  Scenario: Many Events
    Given that there is an Event called "Three Towers" taking place at "31st March 2999"
    And that there is an Event called "Previous Three Towers" taking place at "31st March 2000"
    When I authenticate with the api using email "api@example.com" and password "development"
    And I send a get request to "/api/events"
    Then the response code from the API response should be 200
    And the JSON node "hydra:member" from the API response should be an array of size 2