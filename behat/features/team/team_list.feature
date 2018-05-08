Feature: Team List
  In order for me to review and manage the teams I have created
  As a User, I can see a list of all my teams when I login

  Background:
    Given that "the event" is an Event called "Upcoming Three Towers" taking place "+6 months" from now
    And that "the hike" is a Hike called "Scout Hike" for "the event"
    And that "the user" is a User with email "john@acme.co" and password "Password1!"

  Scenario: I can see my team when I log in
    Given that "the user's team" is a Team called "Alpha Team" for "the hike" registered by "the user"
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should contain "Alpha Team"

  Scenario: I cannot see teams that are not mine
    Given that "the other user" is a User with email "jane@acme.co" and password "Password1!"
    And that "the other user's team" is a Team called "Alpha Team" for "the hike" registered by "the other user"
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should not contain "Alpha Team"
