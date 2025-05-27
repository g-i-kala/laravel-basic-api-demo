<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Invoice and Customer API

This is a simple Laravel API application designed to manage invoices and customers. It includes authentication and token-based API access, making it secure and easy to integrate with other applications.

## Features

-   **Authentication**: User authentication is implemented via Laravel Breeze, providing a simple and secure login system.
-   **Token-based API Access**: Leveraging Laravel Sanctum, the app supports token-based authentication for secure API access.
-   **Token Management**: UI integration for generating and deleting API tokens.
-   **Core Models**: The application includes basic models for managing invoices and customers.

## Installation

1. **Clone the Repository**:

    ```bash
    git clone https://github.com/yourusername/your-repo-name.git
    cd your-repo-name
    ```

2. **Install Dependencies**:

    ```bash
    composer install
    npm install
    npm run dev
    ```

3. **Environment Configuration**:

    - Copy the `.env.example` file to `.env`:
        ```bash
        cp .env.example .env
        ```
    - Update the `.env` file with your database and other configuration details.

4. **Generate Application Key**:

    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**:

    ```bash
    php artisan migrate
    ```

6. **Serve the Application**:
    ```bash
    php artisan serve
    ```

## API Usage

-   **Authentication**: Use the authentication endpoints provided by Laravel Breeze to log in and obtain an API token.
-   **Token Management**: Access the UI to generate and manage API tokens for secure access.
-   **Endpoints**: The API includes endpoints for managing invoices and customers. For detailed API documentation, please refer to the [API Documentation](API.md).

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your improvements.

## License

This project is open-source and available under the [MIT License](LICENSE).

## Support

For support or questions, please contact [karocreativedesigns@gmail.com](mailto:karocreativedesigns@gmail.com).
