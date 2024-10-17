# Services

This folder contains the service classes responsible for implementing the core business logic of the application. Services interact with models and repositories to perform complex operations, keeping the controllers clean from business logic.

## Files:

- **AuthService.php**: Handles all authentication-related logic, including user login, registration, and validation. This service is responsible for checking credentials, managing sessions, and ensuring security in user authentication.
- **CocktailService.php**: Manages the business logic for cocktail recipes. This includes creating, updating, deleting, and retrieving cocktail data. The service ensures that operations related to cocktails follow the application’s rules and constraints.

- **UserService.php**: Handles the business logic related to users, including profile management, badge earning, and other user-related features. This service interacts with the User model to manage user data, preferences, and achievements.

Each service operates independently from the controllers, allowing for reusable and maintainable code.