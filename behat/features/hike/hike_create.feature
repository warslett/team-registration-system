Feature: Hike Create
  In order for teams to be able to register for a hike
  I need to be able to create hikes in the system

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |

  Scenario: I cannot visit the hike create page if I am not an admin
    Given that "the event" is an Event with the following properties:
      | name               | Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to the Create Hike page for "the event"
    Then the response status code should be 403

  Scenario: I cannot add a hike to an event that does not exist
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to "/events/1/hikes/create"
    Then the response status code should be 404

  Scenario: I can add a hiker to an event
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event with the following properties:
      | name               | Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Manage"
    And I follow "Add hike"
    And I fill in "Name" with "Scout Hike"
    And I fill in "Min walkers" with "3"
    And I fill in "Max walkers" with "4"
    And I fill in "Fee per walker" with "12.00"
    And I press "Save"
    Then the title should be "Three Towers » Team Registration System"
    And there is an alert with the message 'Hike "Scout Hike" successfully added to event "Three Towers"'
