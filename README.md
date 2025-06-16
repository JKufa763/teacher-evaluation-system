# Web-Based Teacher Evaluation System

A modern web application for managing and evaluating teachers, built with Laravel, PHP, and more.

## Features
- User roles: Admin, Teacher, Student, HOD
- Evaluation cycles and performance charts
- Student/teacher subject assignments
- RESTful API

## Tech Stack
- Laravel 12.x (PHP 8.x)
- MySQL/PostgreSQL
- Blade, Bootstrap/Tailwind

## Setup

1. Clone the repo:
   ```
   git clone https://github.com/yourusername/teacher-evaluation-system.git
   cd teacher-evaluation-system
   ```
2. Install dependencies:
   ```
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env` and set your DB credentials.
4. Generate app key:
   ```
   php artisan key:generate
   ```
5. (Optional) Run migrations:
   ```
   php artisan migrate
   ```
   > **Note:** The current migrations in the file do not represent the current state of the web app so a seperate database was and is to be used and it will be present in the i will upload it seperately

## Database

- The `/database/migrations` folder contains migration files.
- I have a custom DB, you can export it as `database.sql` and add instructions in the README:
  > To use the pre-built database, import `database/database.sql` into your MySQL/Postgres instance.

## Usage

- Start the server:
  ```
  php artisan serve
  ```
- Access at `http://localhost:8000`

## Screenshots

![Dashboard Screenshot](screenshots/dashboard.png)
![Evaluation Form](screenshots/evaluation-form.png)

## Credits

- Documentation and codebase inspired by [@YourName](https://github.com/yourusername)
- Special thanks to [@AssistantName] for documentation help

## License

MIT
