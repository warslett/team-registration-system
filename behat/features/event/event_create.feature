Feature: Event Create
  In order for teams to be able to register for a hike
  I need to be able to create events in the system

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |

  Scenario: I cannot visit the event create page if I am not an admin
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#navbar" element should not contain "Event Admin"
    When I go to "/registration/admin/events/create"
    Then the response status code should be 403

  Scenario: I can create an event
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Create Event"
    And I fill in "Name" with "Three Towers"
    And I fill in "Date" with "01/04/2999"
    And I fill in "Registration opens" with "01/11/2998"
    And I fill in "Registration closes" with "01/03/2999"
    And I press "Save"
    Then the title should be "Three Towers » Team Registration System"
    And there is an alert with the message 'Event "Three Towers" successfully created'

  Scenario: I cannot create an event if the event name is already in use
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event with the following properties:
      | name | Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Create Event"
    And I fill in "Name" with "Three Towers"
    And I fill in "Date" with "01/04/2999"
    And I fill in "Registration opens" with "01/11/2998"
    And I fill in "Registration closes" with "01/03/2999"
    And I press "Save"
    Then the title should be "Create Event » Team Registration System"
    And there is an alert with the message 'There were some problems with the information you provided'
