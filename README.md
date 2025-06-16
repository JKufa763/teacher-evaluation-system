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
- HTML
- Javascript(Chart.js)

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
   > **Note:** The current migrations in the file do not represent the current state of the web app so a seperate database was and is to be used and it will be present in         the i will upload it seperately. The tryyyout.sql file will be in the root folder with the database structure in there do a manual importion via the means that will be       described in the next section.

## Database

- The `/database/migrations` folder contains migration files.
- I have a custom DB, you can export it as `tryyout.sql` and add instructions in the README:
  > To use the pre-built database, import `prototype(1).sql` into your MySQL/Postgres instance.

## Usage

- Start the server:
  ```
  php artisan serve
  ```
- Access at `http://localhost:8000`

## Screenshots

There are the png files in the root folder here which will show the various dashboards and reports derived from the system

## Credits

- Documentation and codebase inspired by [@Kufa Joshua](https://github.com/JKufa763)
- Special thanks to [@Kennias Kelvin Muumbe] for documentation help

## License

MIT
