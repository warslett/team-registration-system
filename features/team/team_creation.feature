Feature: Team Creation

  In order for my team to be able to participate in an event
  I need to be able to register their details on the system

  Background:
    Given that there is an Event called "Upcoming Three Towers" taking place "+6 months" from now
    And that there is a Hike called "Scout Hike" for the Event "Upcoming Three Towers"
    And there is a User with email "john@acme.co" and password "Password1!"

  Scenario: I can create a team
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Add Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'

  Scenario: I cannot create a team if the team name is already in use
    Given that there is a Team called "Alpha Team" for the Hike "Scout Hike" on the Event "Upcoming Three Towers" registered by "john@acme.co"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Add Team"
    When I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "New Team » Team Registration System"
    And there is an alert with the message 'There were some problems with the information you provided'

  Scenario: I can create a team if the team name is already in use but on a separate event
    Given that there is an Event called "Previous Three Towers" taking place "-6 months" from now
    And that there is a Hike called "Scout Hike" for the Event "Previous Three Towers"
    And that there is a Team called "Alpha Team" for the Hike "Scout Hike" on the Event "Previous Three Towers" registered by "john@acme.co"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Add Team"
    And I fill in "Name" with "Alpha Team"
    And I select "Scout Hike » Upcoming Three Towers" from "Hike"
    And I press "Save"
    Then the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"
    And there is an alert with the message 'Team "Alpha Team" successfully created for "Scout Hike » Upcoming Three Towers"'

