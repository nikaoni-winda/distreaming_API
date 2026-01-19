# diStreaming API 🎬

The backend REST API for **diStreaming**, a modern video streaming platform (Netflix Clone). Built with Laravel, this API powers the frontend application by providing robust authentication, content management, and user interaction features.

## 🚀 Features

-   **Authentication & Authorization**:
    -   Secure user registration and login using **Laravel Sanctum**.
    -   **Role-Based Access Control (RBAC)**: Distinct permissions for `Admin` and `User`.
    -   Token-based API protection.

-   **Movie Management (Admin)**:
    -   CRUD operations for Movies.
    -   Manage Genres and Actors.
    -   Link Movies with multiple Genres and Actors.
    -   Support for Poster URLs and Trailer URLs.

-   **User Features**:
    -   **Watch History**: Tracks movies watched by users with progress percentage.
    -   **Reviews**: Users can rate and review movies.
    -   **Subscription Plans**: Support for Mobile, Basic, Standard, and Premium plans.
    -   Profile management.

-   **Content Discovery**:
    -   Search movies by title.
    -   Filter by Genre.
    -   Pagination support for better performance.
    -   Trending movies algorithm.

## 🛠️ Tech Stack

-   **Framework**: [Laravel](https://laravel.com/) (PHP)
-   **Database**: MySQL
-   **Authentication**: Laravel Sanctum
-   **API Standard**: RESTful JSON API

## 📂 Database Schema

Key tables included in the schema:
-   `users`: Stores user info, roles, and subscription plans.
-   `movies`: Core movie data (title, duration, poster, trailer, etc.).
-   `genres` & `actors`: Categorization data.
-   `movie_genres` & `movie_actors`: Pivot tables for many-to-many relationships.
-   `reviews`: User ratings and comments.
-   `watch_history`: Tracks user viewing progress.

## ⚡ Installation & Setup

Follow these steps to set up the project locally:

### 1. Prerequisities
Ensure you have the following installed:
-   [PHP](https://www.php.net/) (>= 8.1)
-   [Composer](https://getcomposer.org/)
-   [MySQL](https://www.mysql.com/)

### 2. Clone the Repository
```bash
git clone https://github.com/yourusername/distreaming_API.git
cd distreaming_API
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Environment Configuration
Copy the example environment file and configure your database credentials:
```bash
cp .env.example .env
```
Open `.env` and set your database connection:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=distreaming_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Run Migrations
Create the database tables:
```bash
php artisan migrate
```

### 7. Run the Server
Start the development server:
```bash
php artisan serve
```
The API will be available at `http://localhost:8000`.

## 📡 API Endpoints Overview

### Authentication
-   `POST /api/register` - Register a new user
-   `POST /api/login` - Login and receive token
-   `POST /api/logout` - Logout (requires token)

### Movies
-   `GET /api/movies` - List all movies (with search/filter)
-   `GET /api/movies/{id}` - Get movie details
-   `POST /api/movies` - Create movie (Admin only)
-   `PUT /api/movies/{id}` - Update movie (Admin only)
-   `DELETE /api/movies/{id}` - Delete movie (Admin only)

### User Actions
-   `POST /api/watch-history` - Add/Update watch progress
-   `POST /api/reviews` - Add a review
-   `GET /api/users/profile` - Get current user profile

*(See `routes/api.php` for the full list of endpoints)*

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
