Feature: Event Show
  In order for me to review and manage an Event in the system
  As an Admin, I can view all the details for an Event on the Event's page

  Background:
    Given that "the user" is a User with the following properties:
      | email    | john@acme.co |
      | password | Password1!   |

  Scenario: I can visit the Event page for an Event
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Manage"
    Then the response status code should be 200
    And the title should be "Upcoming Three Towers » Team Registration System"

  Scenario: The Event's Hikes are listed on the page
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event
    And that "the event" has the following Hikes:
      | name          |
      | Scout Hike    |
      | Explorer Hike |
      | Classic Hike  |
      | Jubilee Hike  |
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Manage"
    Then the following Hikes are listed on the page:
      | Name          | Teams   |
      | Scout Hike    | 0 Teams |
      | Explorer Hike | 0 Teams |
      | Classic Hike  | 0 Teams |
      | Jubilee Hike  | 0 Teams |

  Scenario: The Event's Hike's Teams are counted on the page
    Given that there is a User Group with the role "ROLE_ADMIN" with the following members:
      | Reference |
      | the user  |
    And that "the event" is an Event
    And that "the hike" is a Hike for "the event" with the following properties:
      | name | Scout Hike |
    And that "the hike" has 5 teams registered by "the user"
    When I log in with email "john@acme.co" and password "Password1!"
    And I follow "Event Admin"
    And I follow "Manage"
    Then the following Hikes are listed on the page:
      | Name       | Teams   |
      | Scout Hike | 5 Teams |

  Scenario: I cannot visit the Event page for an Event if I am not an admin
    Given that "the event" is an Event with the following properties:
      | name | Upcoming Three Towers |
    When I log in with email "john@acme.co" and password "Password1!"
    Then the response should not contain "Event Admin"
    When I go to the Event page for "the event"
    Then the response status code should be 403