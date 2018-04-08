Feature:
  In order for me to review and manage the teams I have created
  As a User, I can see a list of all my teams when I login

  Scenario: I can see my team when I log in
    Given that there is an Event called "Scout Hike 2019"
    And there is a User with email "john@acme.co" and password "Password1!"
    And that there is a Team called "Alpha Team" for the Event "Scout Hike 2019" registered by "john@acme.co"
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should contain "Alpha Team"

  Scenario: I cannot see teams that are not mine
    Given that there is an Event called "Scout Hike 2019"
    And there is a User with email "john@acme.co" and password "Password1!"
    And there is a User with email "jane@acme.co" and password "Password1!"
    And that there is a Team called "Alpha Team" for the Event "Scout Hike 2019" registered by "jane@acme.co"
    When I log in with email "john@acme.co" and password "Password1!"
    Then the "#teams" element should not contain "Alpha Team"
