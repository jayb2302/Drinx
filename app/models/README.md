# Models

This folder contains the models that represent the database entities in the application. Each model corresponds to a table in the database and defines the attributes and behaviors of specific entities.

## Files:

- **User.php**: Represents the User entity in the system. This model includes attributes like user ID, email, password, and profile information. It also handles user-related behavior and relationships, such as authentication and profile updates.

- **Cocktail.php**: Represents the Cocktail entity in the system. This model includes attributes such as cocktail ID, title, ingredients, and other details relevant to a cocktail recipe. It manages operations related to cocktail creation, modification, and deletion.

- **Badge.php**: Represents the Badge entity. This model includes attributes like badge ID, badge name, and description. It defines the different types of badges users can earn and the criteria for achieving them.

Each model interacts with the database and may establish relationships with other entities.