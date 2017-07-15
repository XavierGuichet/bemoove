Feature: Manage user
  In order to use the service
  As a unlogged user
  I need to be able to create an account with a password and a login,
  And use it to log me in the service

  Background:
    Given the database is clean
    Given there are users:
    | password | email                      |
    | 123456   | existinguser@bemoove.com   |

  # the "@createSchema" annotation provided by API Platform creates a temporary SQLite database for testing the API
  Scenario Outline: Create an account
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/accounts" with body:
    """
    {
      "email": <email>,
      "password": <password>,
      "token": <token>,
    }
    """
    Then the response status code should be <response_code>
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"

    Examples:
      | email                       | token         | password                      | response_code |
      | "testuser@bemoove.fr"       | "null"        | "validpassword"               | 201           |
      | "testcoach@bemoove.fr"      | "valide"      | "validpassword"               | 201           |
      | "testcoach@bemoove.fr"      | "invali"      | "validpassword"               | 400           |
      | "mailinvalid.fr"            | "null"        | "validpassword"               | 400           |
      | "testuser@bemoove.fr"       | "null"        | "inva"                        | 400           |
      | "existinguser@bemoove.com"  | "ROLE_USER"   | "existinguser@bemoove.com"    | 400           |

  Scenario: Nothing End
