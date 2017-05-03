Feature: Manage address
  In order to manage my addresses
  As a Coach or a user
  I need to be able to retrieve mine, create a new and delete them trough the API.

  # the "@createSchema" annotation provided by API Platform creates a temporary SQLite database for testing the API
  @createSchema
  Scenario: Create an address
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/books" with body:
    """
    {
      "firstline": "3 rue des mouettes",
      "secondline": "",
      "city": "Rezé",
      "latitude": "",
      "longitude": "",
      "name": "",
      "user": "",
      "editable": ,
      "postalCode": "44400"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
    And the JSON should be equal to:
    """
    {
      "@context": "/contexts/Book",
      "@id": "/books/1",
      "@type": "Book",
      "isbn": "9781782164104",
      "title": "Persistence in PHP with the Doctrine ORM",
      "description": "This book is designed for PHP developers and architects who want to modernize their skills through better understanding of Persistence and ORM.",
      "author": "K\u00e9vin Dunglas",
      "publicationDate": "2013-12-01T00:00:00+00:00",
      "reviews": []
    }
    """

  # The "@dropSchema" annotation must be added on the last scenario of the feature file to drop the temporary SQLite database
  @dropSchema
    Scenario: Add a review
    When I add "Content-Type" header equal to "application/ld+json"
    When I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/reviews" with body:
    """
    {
      "rating": 5,
      "body": "Must have!",
      "author": "Foo Bar",
      "publicationDate": "2016-01-01",
      "book": "/books/1"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
    And the JSON should be equal to:
    """
    {
      "@context": "/contexts/Review",
      "@id": "/reviews/1",
      "@type": "Review",
      "rating": 5,
      "body": "Must have!",
      "author": "Foo Bar",
      "publicationDate": "2016-01-01T00:00:00+00:00",
      "book": "/books/1"
    }
    """
