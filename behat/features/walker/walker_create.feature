Feature: Walker Create
  In order for my team to be able to participate in an event
  I need to be able to add each of my walkers to my team

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |
    And that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
      | date | 2050-04-01            |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name       | Scout Hike |
      | maxWalkers | 4          |
      | minAge     | 10         |
      | maxAge     | 14         |

  Scenario: I cannot add a walker to another user's team
    Given that "the other user" is a User
    And that "the team" is a Team for "the hike" registered by "the other user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to the Create Walker page for "the team"
    Then the response status code should be 403

  Scenario: I cannot add a walker to a team that has the maximum number of walkers
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    And that "the team" has 4 walkers
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    And I follow "Add walker"
    Then the title should be "Alpha Team » Team Registration System"
    And there is an alert with the message "This team already has the maximum number of allowed walkers"

  Scenario: I cannot add a walker to a team that does not exist
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to "/teams/1/walkers/create"
    Then the response status code should be 404

  Scenario: I can add a walker to my team
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    And I follow "Add walker"
    And I fill in "Forename" with "John"
    And I fill in "Surname" with "Smith"
    And I fill in "Date of birth" with "01/01/2039"
    And I fill in "Emergency Contact Number" with "0123456789"
    And I press "Save"
    Then the title should be "Alpha Team » Team Registration System"
    And there is an alert with the message 'Walker "John Smith" successfully added to team "Alpha Team"'
