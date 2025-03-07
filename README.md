# Boiler Plate Application

## About

Boiler Plate is a pre-built authentication system that provides reusable functionality for login, sign-up, email verification, OTP authentication, user profile management, and logout. It is designed to be easily integrated into any Laravel project, allowing developers to focus on core business logic instead of implementing authentication from scratch.

## Features
- User Registration & Login
- Email Verification
- OTP-based Authentication
- Profile Management
- Logout Functionality

## Installation

Follow these steps to set up and use the Boiler Plate application:

### Prerequisites
Ensure you have the following installed on your system:
- PHP (>=8.0)
- Composer
- MySQL
- Laravel (>=11)


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
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

6. Generate the application key:
   ```sh
   php artisan key:generate
   ```

7. Run database migrations:
   ```sh
   php artisan migrate
   ```

8. (Optional) Seed the database with default data:
   ```sh
   php artisan db:seed
   ```

9. Start the development server:
   ```sh
   php artisan serve
   ```

Your application should now be running at `http://127.0.0.1:8000/`.

## Contribution
Contributions are welcome! Feel free to fork this repository, submit issues, or make pull requests to improve the Boiler Plate application.

---

For any questions or support, feel free to reach out to the repository maintainers or open an issue.

