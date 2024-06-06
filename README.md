# Mobile Running Tracker Application

This is a mobile running tracker application built using Laravel 11 and PHP 8.2. The application allows users to
register, log in, and track their running sessions. It includes features such as leaderboards, user challenges, and
secure authentication using Laravel Sanctum.

## Features

- **User Authentication**: Register, log in, and log out using Laravel Sanctum.
- **Running Sessions**: Track and manage running sessions.
- **User Management**: View and update user profiles.
- **Challenges**: Participate in daily, weekly, and monthly challenges.
- **Leaderboards**: View leaderboards based on running statistics.
- **User Points**: Track and display user points.

## Requirements

- PHP 8.2
- Laravel 11
- MySQL

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/yourrepository.git
   cd yourrepository

2. **Install dependencies:**
    ```bash
   composer install

3. **Copy the .env file and configure your environment:**
    ```bash
   cp .env.example .env

4. **Generate an application key:**
    ```bash
   php artisan key:generate
5. **Configure your database in the .env file:**
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password

6. **Run database migrations:**
    ```bash
   php artisan migrate

## Running the Application

1. **Start the development server:**
   ```bash
    php artisan serve

2. **Access the application in your browser:**
    ```bash
   http://localhost:8000

## API Endpoints

### Public Routes

- `POST /register`: Register a new user
- `POST /login`: Log in
- `POST /forgot`: Forgot password
- `POST /password/reset`: Reset password

### Protected Routes

These routes require authentication using Laravel Sanctum.

#### Running Sessions

- `POST /run/session`: Create a new running session
- `GET /run/session`: Get all running sessions
- `DELETE /run/session/{runningSession}`: Delete a running session

#### User Management

- `GET /user`: Get authenticated user's profile
- `GET /user/index`: Get all users
- `GET /user/{user}`: Get a specific user's profile
- `PATCH /user`: Update authenticated user's profile
- `DELETE /user/{user}`: Delete a user

#### User Challenges

- `POST /user/challenges`: Create a new user challenge
- `GET /user/challenges`: Get user challenges
- `DELETE /user/challenges`: Delete a user challenge

#### Challenges

- `GET /challenges/check`: Check and set new challenges
- `POST /challenges`: Create a new challenge
- `GET /challenges`: Get all challenges
- `DELETE /challenges/{challenge}`: Delete a challenge

#### Leaderboard

- `GET /leaderboard`: Get the leaderboard

#### User Points

- `GET /user_points`: Get authenticated user's points
    
