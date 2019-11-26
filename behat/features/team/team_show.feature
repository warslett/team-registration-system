Feature: Team Show
  In order for me to review and manage a team I have created
  As a User, I can visit my Team's page when I login

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |
    And that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    And that "the hike" is a Hike for "the event" with the following properties:
      | name         | Scout Hike |
      | minWalkers   | 3          |
      | feePerWalker | 12         |

  Scenario: I can visit my Team's page
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    Then the response status code should be 200
    And the title should be "Alpha Team » Team Registration System"

  Scenario: My Team's Walkers are listed on the page
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    And that "the team" has the following Walkers:
      | foreName | surName |
      | John     | Smith   |
      | Paul     | Smith   |
      | George   | Smith   |
      | Ringo    | Smith   |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    Then the following Walkers are listed on the page:
      | Name         |
      | John Smith   |
      | Paul Smith   |
      | George Smith |
      | Ringo Smith  |

  Scenario: I cannot see the payment button until my team has the minimum number of walkers
    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
      | name | Alpha Team |
    And that "the team" has 2 walkers
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Alpha Team"
    Then I should not see a "#payment-btn" element

    # Commented out while fees disabled
#  Scenario Outline: I cannot see the payment button with the correct value to pay when my team has enough walkers
#    Given that "the team" is a Team for "the hike" registered by "the user" with the following properties:
#      | name | Alpha Team |
#    And that "the team" has "<Number of Walkers>" walkers
#    When I log in with email "john@acme.co" and password "Password1!"
#    And I follow "Alpha Team"
#    Then the "#payment-btn" element should contain "<Fee Due>"
#    Examples:
#      | Number of Walkers | Fee Due |
#      | 3                 | £36     |
#      | 4                 | £48     |

  Scenario: I cannot visit a Team page for a Team I did not register
    Given that "the other user" is a User
    And that "the team" is a Team for "the hike" registered by "the other user"
    When I log in with email "john@acme.co" and password "Password1!"
    And I go to the Team page for "the team"
    Then the response status code should be 403
