Feature:
  In order to use the functionality of the system
  I need to be able to log in as a User.

  Scenario: I can login as a user
    Given there is a User with email "john@acme.co" and password "Password1!"
    When I go to "/user/login"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I press "Log in"
    Then I should be on "/team/list"
    And I am logged in as "john@acme.co"