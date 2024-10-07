# Views

This folder contains the presentation logic of the application. Views are responsible for rendering HTML and displaying data passed from controllers. They are organized based on different sections of the application, including layouts, authentication, cocktails, admin, and user profiles.

## Folders and Files:

### **/layout**
- **header.php**: Contains the site's navigation and header layout, included across multiple pages.
- **footer.php**: Contains the site's footer layout, used across multiple pages.

### **/auth**
- **login.php**: The login page where users can input their credentials to access the system.
- **register.php**: The registration page where new users can create an account.

### **/cocktails**
- **index.php**: Displays a list of all available cocktails.
- **view.php**: Shows detailed information about a specific cocktail, including ingredients and preparation steps.
- **add.php**: A form where users can submit a new cocktail recipe.

### **/admin**
- **dashboard.php**: The main admin dashboard, providing an overview of site metrics and management options.
- **manage_users.php**: A page where the admin can view, edit, and manage user accounts.
- **manage_badges.php**: A page where the admin can create and manage badges for users.

### **/user**
- **profile.php**: Displays the user's profile information, including badges and personal details.
- **settings.php**: Allows the user to update their account settings, such as email, password, and profile info.

Each view is responsible for rendering data passed by controllers and is designed to be reused and modular, ensuring consistency throughout the application's interface.