# Repositories

This folder contains repository classes that provide an abstraction layer for accessing and managing data in the database. Repositories handle all CRUD operations (Create, Read, Update, Delete) for specific entities, keeping the business logic separate from data access logic.

## Files:

- **UserRepository.php**: Handles CRUD operations related to user data. This includes retrieving user profiles, updating user information, and managing user-related queries.

- **CocktailRepository.php**: Manages CRUD operations for cocktail recipes. This includes saving new cocktail entries, retrieving existing recipes, updating them, and deleting them when necessary.

- **BadgeRepository.php**: Handles CRUD operations for badges. It manages how badges are created, updated, and retrieved from the database and how they are assigned to users.

Each repository ensures that database access is structured, reusable, and easy to maintain, abstracting complex queries from the rest of the application.