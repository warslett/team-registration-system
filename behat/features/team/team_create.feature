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
    Given that "the event" is an Event called "Previous Three Towers" taking place "1 day" from now and taking registrations from "-7 months" from now until "-1 months" from now
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    Then the title should be "Teams » Team Registration System"
    And there is an alert with the message 'There are currently no events open for registration'

  Scenario: I can only register Teams for events that are open for registration
    Given that "this year's event" is an Event called "Upcoming Three Towers" taking place "+4 months" from now and taking registrations from "-2 months" from now until "+3 months" from now
    And that "this year's hike" is a Hike called "Scout Hike" for "this year's event"
    And that "last year's event" is an Event called "Previous Three Towers" taking place "-10 months" from now and taking registrations from "-16 months" from now until "-11 months" from now
    And that "last year's hike" is a Hike called "Scout Hike" for "last year's event"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    Then the drop down "Hike" includes the option "Scout Hike » Upcoming Three Towers"
    And the drop down "Hike" does not include the option "Scout Hike » Previous Three Towers"

  Scenario: I can create a team
    Given that "the event" is an Event called "Upcoming Three Towers" taking place "+6 months" from now
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'

  Scenario: I cannot create a team if the team name is already in use
    Given that "the event" is an Event called "Upcoming Three Towers" taking place "+6 months" from now
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    And that "the existing team" is a Team called "Alpha Team" for "the hike" registered by "the user"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    When I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Register team » Team Registration System"
    And there is an alert with the message 'There were some problems with the information you provided'

  Scenario: I can create a team if the team name is already in use but on a separate event
    Given that "the event" is an Event called "Upcoming Three Towers" taking place "+6 months" from now
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    Given that "last year's event" is an Event called "Previous Three Towers" taking place "-6 months" from now
    And that "last year's hike" is a Hike called "Scout Hike" for "last year's event"
    And that "last year's team" is a Team called "Alpha Team" for "last year's hike" registered by "the user"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Register Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'
