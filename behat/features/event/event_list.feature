Feature: Event List
  In order for me to manage the events in the system
  As an Administrator, I can see a list of all events

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |

  Scenario: I cannot visit the event list page if I am not an admin
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#navbar" element should not contain "Event Admin"
    When I go to "/events/list"
    Then the response status code should be 403

  Scenario: No Events
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    Then the response status code should be 200
    And the "#events" element should contain "No Events"

  Scenario: One Event
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    Then the response status code should be 200
    And the "#events" element should contain "Upcoming Three Towers"

  Scenario: Multiple Events are ordered by date
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "previous event" is an Event with the following properties:
      | name | Previous Three Towers |
      | date | 2000-03-31            |
    And that "upcoming event" is an Event with the following properties:
      | name | Upcoming Three Towers |
      | date | 2999-03-31            |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    Then the events table lists "Upcoming Three Towers" in row 1
    And the events table lists "Previous Three Towers" in row 2