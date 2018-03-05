Feature: Manage user
  In order to use the service
  As a unlogged user
  I need to be able to create an account with a password and a login,
  And use it to log me in the service

  @createSchema
  Scenario: Nothing Start

  Scenario Outline: Create an account
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/accounts" with body:
    """
    {
      "email": <email>,
      "password": <password>,
      "isCoach" : <isCoach>
    }
    """
    Then the response status code should be <response_code>
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
    And the user <email> should have role <role>

    Examples:
      | email                       | isCoach       | password                      | response_code | role           |
      # Création d'un compte utilisateur normal
      | "testuser@bemoove.fr"       | false         | "validpassword"               | 201           | "ROLE_USER"    |
      # Création d'un compte utilisateur avec la meme adresse qu'un existant
      | "testuser@bemoove.fr"       | false         | "validpassword"               | 400           | ""             |
      # Création d'un compte coach normal
      | "testcoach@bemoove.fr"      | true          | "validpassword"               | 201           | "ROLE_PARTNER" |
      # Création d'un compte coach avec la meme adresse qu'un existant
      | "testcoach@bemoove.fr"      | true          | "validpassword"               | 400           | ""             |
      # Création d'un compte avec un mail invalide
      | "mailinvalid.fr"            | false         | "validpassword"               | 400           | ""             |
      # Création d'un compte avec un mot de passe trop court
      | "testuser@bemoove.fr"       | true          | "inva"                        | 400           | ""             |

  Scenario: Login with an User account
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/login_check" with body:
    """
    {
      "email": "testuser@bemoove.fr",
      "password": "validpassword"
    }
    """
    Then the response status code should be 200
    And the response should contain "token"

  Scenario: Get forgotten password token
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/send_forgotten_password_token" with body:
    """
    {
        "email": "testuser@bemoove.fr"
    }
    """
    Then the response status code should be 201

  Scenario: Change forgotten password
    Given There is a forgotten password token "tokenforgotten" for email "testuser@bemoove.fr"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/change_forgotten_password" with body:
    """
    {
        "token": "tokenforgotten",
        "password": "newpassword"
    }
    """
    Then the response status code should be 201


  Scenario: Fail to log with old password
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/login_check" with body:
    """
    {
      "email": "testuser@bemoove.fr",
      "password": "validpassword"
    }
    """
    Then the response status code should be 401

  Scenario: Login with new password
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/login_check" with body:
    """
    {
        "email": "testuser@bemoove.fr",
        "password": "newpassword"
    }
    """
    Then the response status code should be 200
    And the response should contain "token"

  @dropSchema
  Scenario: Nothing End
