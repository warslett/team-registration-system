Feature: Team List
  In order for me to review and manage the teams I have created
  As a User, I can see a list of all my teams when I login

  Background:
    Given that "the event" is an Event
    And that "the hike" is a Hike for "the event"
    And that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |

  Scenario: I can see my team when I log in
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should contain "Alpha Team"

  Scenario: I cannot see teams that are not mine
    Given that "the other user" is a User
    And that "the team" is a Team for "the hike" registered by "the other user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should not contain "Alpha Team"
