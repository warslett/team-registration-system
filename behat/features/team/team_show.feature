Feature: Team Show
  In order for me to review and manage a team I have created
  As a User, I can visit my Team's page when I login

  Background:
    Given that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name | Scout Hike |
    And that "the user" is a User with email "john@acme.co" and password "Password1!"

  Scenario: I can visit my Team's page
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    Then the response status code should be 200
    And the title should be "Alpha Team » Scout Hike » Upcoming Three Towers » Team Registration System"

  Scenario: My Team's Walkers are listed on the page
    And that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    And that "the team" has the following Walkers:
      | Forename | Surname | Reference Character | Emergency Contact Number |
      | John     | Smith   | A                   | 123456789                |
      | Paul     | Smith   | B                   | 123456789                |
      | George   | Smith   | C                   | 123456789                |
      | Ringo    | Smith   | D                   | 123456789                |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    Then the following Walkers are listed on the page:
      | Name         |
      | John Smith   |
      | Paul Smith   |
      | George Smith |
      | Ringo Smith  |

  Scenario: I cannot visit a Team page for a Team I did not register
    Given that "the other user" is a User with email "jane@acme.co" and password "Password1!"
    And that "the team" is a Team for "the hike" registered by "the other user"
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to the Team page for "the team"
    Then the response status code should be 403
