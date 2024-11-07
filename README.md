# MVC template

A simple and lightweight MVC template for PHP projects.

## Features

- MVC structure controlled by router
    - Models - simple wrapper classes for database queries (often tables)
    - Servicies - handle model queries
    - Views - PHTML files for displaing data
    - Controllers - request handling and data processing
    - Router - url parsing and routing
- Components for views and controllers
- PDO database wrapper
- Simple error handling
- Configurable routes
- Simple authentication system
- Basic popup messages

## Usage notes

### Structure

- Write your models, services, views and controllers in `App/` directory
- `App/Core` is meant to be mostly untouched
    - `AUtility` - has projectwide useful functions - every class should extend this
    - `AController` every controller has to extend
    - `AAuthController` every controller that requires authentication has to extend this
    - `AUtility -> AController -> AAuthController`

### Config

- `config/routes.php` - define routes for your application
- `config/general.php` - holds general configuration (database connection, etc.)

### Components

- Works as calling action and rendering some other controller in the middle of view rendering
    - action - method in controller - provides data and sets view
- It's needed to set components ahead
    - Array `components` - structure `name => [controller => action]`

### Authentication

- `App/Core/AAuthController` - has methods for checking forcing authentication
- `App/Controllers/LoginController` - has methods for login and logout
- `App/Controllers/RegisterController` - has methods for registration

### Utils

- `App/Core/AUtility` - has useful functions for project
    - `error($message)` - display error message as popup
    - `fatal($message)` - redirect to error page with message
    - `redirect($url)` - redirect to another page
    - `success($message)` - display message as popup
    - `route($route)` - returns absolute url for route
    - `getAbsoluteUrl()` - returns current absolute url
    - and more
- `App/Core/Db/Db.php` - PDO wrapper for database connection
    - example usage in `App/Models/UserService.php`  