# PHP MVC Template

A simple, lightweight, and extensible Model-View-Controller (MVC) template for building PHP web applications. This project provides a robust foundation for rapid development, featuring a modular structure, integrated authentication, and essential utilities.

## Features

- **MVC Architecture** managed via a flexible router:
    - **Models:** Lightweight wrappers for database tables and queries.
    - **Services:** Handle business logic and coordinate model interactions.
    - **Views:** PHTML files for clean, organized presentation of data.
    - **Controllers:** Manage HTTP request handling and data processing.
    - **Router:** Parses URLs and dispatches requests to the appropriate controllers.
- **Reusable Components** for views and controllers.
- **PDO Database Wrapper** for secure, efficient database access.
- **Simple Error Handling** and popup-based messaging.
- **Configurable Routes** for flexible application structure.
- **Basic Authentication System** for user login and registration flows.
- **Flash Message Support** for popups and notifications.

## Getting Started

### Directory Structure

- Application code resides in the `App/` directory:
    - `App/Models`, `App/Services`, `App/Views`, `App/Controllers`
- Core framework code is in `App/Core` (should generally not be modified):
    - `AUtility` — Base utility class, provides project-wide helper methods (all classes should extend this).
    - `AController` — Abstract base for all controllers.
    - `AAuthController` — Extend for controllers requiring authentication.
    - Inheritance: `AUtility` → `AController` → `AAuthController`

### Configuration

- Define routes in `config/routes.php`.
- Set general application settings (database, etc.) in `config/general.php`.

### Components

- Components enable calling actions and rendering outputs of other controllers from within a view.
- Define components in the `components` array as `name => [controller => action]`.
- Components must be registered in advance for use in views.

### Authentication

- `App/Core/AAuthController`: Provides authentication enforcement and helper methods.
- `App/Controllers/LoginController`: Implements login and logout functionality.
- `App/Controllers/RegisterController`: Implements user registration.

### Utilities

- `App/Core/AUtility` provides essential helper methods:
    - `error($message)` — Show an error popup.
    - `fatal($message)` — Redirect to an error page with a message.
    - `redirect($url)` — Redirect to a given URL.
    - `success($message)` — Show a success popup.
    - `route($route)` — Generate an absolute URL for a named route.
    - `getAbsoluteUrl()` — Retrieve the current page's absolute URL.
    - ...and more.
- `App/Core/Db/Db.php`: PDO-based database connection wrapper (see usage in, e.g., `App/Models/UserService.php`).

## Usage Notes

- Keep your application logic organized by placing models, services, views, and controllers in their respective folders under `App/`.
- Avoid modifying the framework code in `App/Core` unless you are extending or customizing the framework.
- Follow the provided inheritance structure for controllers to ensure authentication and utility methods are accessible.

## Contributing

Contributions, bug reports, and suggestions are welcome! Please open an issue or submit a pull request.

---

**License:** MIT  
**Author:** [Komareal](https://github.com/Komareal)
