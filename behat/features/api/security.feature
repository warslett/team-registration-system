Feature: API Security
  In order to make requests for secure data in the API
  As a user, I can request a JWT Token which I can include in all other requests for authorization

  Background:
    Given that "the api user" is a User with the following properties:
      | email    | api@example.com |
      | password | development     |

  Scenario: Authenticating
    When I authenticate with the api using email "api@example.com" and password "development"
    Then the response code from the API response should be 200
    And the JSON node "token" is included in the response from the API