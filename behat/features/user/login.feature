Feature: User Login
  In order to use the functionality of the system
  I need to be able to log in as a User.

  Scenario: I can login as a user
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |
    When I go to "/registration/user/login"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I press "Log in"
    Then the title should be "Teams » Team Registration System"

  Scenario: I cannot login with an email that does not belong to a user in the system
    When I go to "/registration/user/login"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "Password1!"
    And I press "Log in"
    Then the title should be "Log in » Team Registration System"
    And there is an alert with the message "Invalid credentials."

  Scenario: I cannot login with the wrong password
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |
    When I go to "/registration/user/login"
    And I fill in "Email" with "john@acme.co"
    And I fill in "Password" with "wRonGpASSwOrd"
    And I press "Log in"
    Then the title should be "Log in » Team Registration System"
    And there is an alert with the message "Invalid credentials."