# Controllers

This folder contains the controllers responsible for handling HTTP requests and routes. Each controller corresponds to specific features of the application and processes requests, invokes models and services, and returns the appropriate views or responses.

## Files:

- **AuthController.php**: Handles user authentication actions, including login, registration, and logout. This controller manages user sessions and validation.
  
- **CocktailController.php**: Manages all actions related to cocktails, such as adding new recipes, editing existing ones, and deleting them.

- **CommentController.php**: Handles the actions for managing comments on cocktail recipes. This includes adding new comments, editing, and deleting them.

- **LikeController.php**: Manages the "like" functionality for cocktails. It allows users to like or unlike cocktail recipes.

- **UserController.php**: Handles user profile and settings actions. This includes viewing and editing user information, managing badges, and updating user preferences.

Each controller interacts with models and services to fulfill the required business logic.