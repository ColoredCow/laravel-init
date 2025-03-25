# Boilerplate Application

## About

Boilerplate is a pre-built authentication system that provides reusable functionality for login, sign-up, email verification, OTP authentication, user profile management, and logout. It is designed to be easily integrated into any Laravel project, allowing developers to focus on core business logic instead of implementing authentication from scratch.

## Features
- User Registration & Login
- Email Verification
- Profile Management
- Logout Functionality

## Installation

Follow these steps to set up and use the application:

### Setup Instructions

1. Clone the repository:
   ```sh
   git clone https://github.com/ColoredCow/laravel-init.git
   ```

2. Navigate into the project directory:
   ```sh
   cd laravel-init
   ```

3. Install dependencies using Composer:
   ```sh
   composer install
   ```

4. Copy the example environment file:
   ```sh
   cp .env.example .env
   ```

5. Update the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_init
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Configure your mail settings for email verification:
   Registration requires email verification, so ensure your mail settings are correctly configured in the `.env` file:
   ```env
   MAIL_MAILER=smtp
   MAIL_SCHEME=null
   MAIL_HOST=localhost
   MAIL_PORT=1025
   MAIL_USERNAME=maildev
   MAIL_PASSWORD=maildev
   ```

7. Generate the application key:
   ```sh
   php artisan key:generate
   ```

8. Run database migrations:
   ```sh
   php artisan migrate
   ```

9. (Optional) Seed the database with default data:
   ```sh
   php artisan db:seed
   ```

10. Start the development server:
    -  Using `serve`
      ```sh
      php artisan serve
      ```
      Your application should now be running at [**http://127.0.0.1:8000**](http://127.0.0.1:8000)
    - Using [Laravel Valet](https://laravel.com/docs/12.x/valet#main-content)
      ```sh
      valet link laravel-init

      valet secure laravel-init
      ```
      Your application should now be running at [**https://laravel-init.test**](https://laravel-init.test)

## Test Admin Login

You can use the following test admin credentials to log in:
- **Email:** admin@admin.com
- **Password:** admin@123

## Contribution
Contributions are welcome! Feel free to fork this repository, submit issues, or make pull requests to improve the Boiler Plate application.

---

For any questions or support, feel free to reach out to the repository maintainers or open an issue.
