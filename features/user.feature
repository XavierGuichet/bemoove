Feature: Manage user
  In order to use the service
  As a unlogged user
  I need to be able to create an account with a password and a login,
  And use it to log me in the service

  # the "@createSchema" annotation provided by API Platform creates a temporary SQLite database for testing the API
  Scenario: Create an account
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/users" with body:
    """
    {
      "email": "bemoovetestcoach@yopmail.com",
      "password": "motdepasse"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"

    @dropSchema
