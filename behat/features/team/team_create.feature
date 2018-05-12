Feature: Team Create
  In order for my team to be able to participate in an event
  I need to be able to register their details on the system

  Background:
    Given that "the user" is a User with email "john@acme.co" and password "Password1!"

  Scenario: I cannot add a team if there are no Events
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    Then the title should be "Teams » Team Registration System"
    And there is an alert with the message 'There are currently no events open for registration'

  Scenario: I cannot add a team if none of the Events are open for registration
    Given that "the event" is an Event with the following properties:
      | dateIn               | +1 day    |
      | registrationOpensIn  | -7 months |
      | registrationClosesIn | -1 months |
    And that "the hike" is a Hike for "the event"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    Then the title should be "Teams » Team Registration System"
    And there is an alert with the message 'There are currently no events open for registration'

  Scenario: I can only register Teams for events that are open for registration
    Given that "this year's event" is an Event with the following properties:
      | name                 | Upcoming Three Towers |
      | dateIn               | +4 months             |
      | registrationOpensIn  | -2 months             |
      | registrationClosesIn | +3 months             |
    And that "this year's hike" is a Hike for "this year's event" with the following properties:
      | name | Scout Hike |
    And that "last year's event" is an Event with the following properties:
      | name                 | Previous Three Towers |
      | dateIn               | -10 months            |
      | registrationOpensIn  | -16 months            |
      | registrationClosesIn | -11 months            |
    And that "last year's hike" is a Hike for "last year's event" with the following properties:
      | name | Scout Hike |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    Then the drop down "Hike" includes the option "Scout Hike » Upcoming Three Towers"
    And the drop down "Hike" does not include the option "Scout Hike » Previous Three Towers"

  Scenario: I can create a team
    Given that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name | Scout Hike |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'

  Scenario: I cannot create a team if the team name is already in use
    Given that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name | Scout Hike |
    And that "the existing team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    When I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Register team » Team Registration System"
    And there is an alert with the message 'There were some problems with the information you provided'

  Scenario: I can create a team if the team name is already in use but on a separate event
    Given that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name | Scout Hike |
    Given that "the other event" is an Event
    And that "the other hike" is a Hike for "the other event" with the following properties:
      | name | Scout Hike |
    And that "the other team" is a Team for "the other hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'
