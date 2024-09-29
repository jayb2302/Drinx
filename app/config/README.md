# Config

This folder contains configuration files for the application. These files are used to set up environment-specific settings, such as database connections, API keys, and other application-wide settings.

## Files:

- **database.php**: This file contains the configuration for connecting to the database, typically using a PDO connection. It includes settings like the database host, username, password, and database name.

### Example of `database.php`:
```php
<?php
return [
    'host' => 'localhost',
    'db_name' => 'drinx_db',
    'username' => 'root',
    'password' => 'password',
    'charset' => 'utf8mb4',
    'dsn' => 'mysql:host=database;dbname=drinx_db;charset=utf8mb4'
];
?>