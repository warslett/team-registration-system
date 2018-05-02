Feature:
  In order to login to the system,
  I need to be able to register an account

  Scenario: I can register an account with valid credentials
    When I go to "/user/register"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I fill in "Repeat Password" with "Password1!"
    And I press "Register"
    Then I should be on "/user/login"
    And there is an alert with the message "Registration successful"
    When I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I press "Log in"
    Then I should be on "/team/list"
    And I should see "Welcome, john@acme.co"

  Scenario: I cannot register an account with an invalid email
    When I go to "/user/register"
    And I fill in "Email" with "invalidEmail"
    And I fill in "Password" with "Password1!"
    And I fill in "Repeat Password" with "Password1!"
    And I press "Register"
    Then I should be on "/user/register"
    And there is an alert with the message "There were some problems with the information you provided"

  Scenario: I cannot register an account with an email that already exists in the system
    Given there is a User with email "john@acme.co" and password "Password1!"
    When I go to "/user/register"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I fill in "Repeat Password" with "Password1!"
    And I press "Register"
    Then I should be on "/user/register"
    And there is an alert with the message "There were some problems with the information you provided"

  Scenario: I cannot register an account if my passwords don't match
    When I go to "/user/register"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I fill in "Repeat Password" with "Password2!"
    And I press "Register"
    Then I should be on "/user/register"
    And there is an alert with the message "There were some problems with the information you provided"