# Car Expense Tracker

A simple web application for tracking car-related expenses such as fuel, maintenance, insurance, and parking.

Built with pure PHP (no frameworks) and MySQL as a school project.

## Features

- User registration and login
- Add and manage multiple vehicles
- Track expenses per vehicle (fuel, maintenance, insurance, parking, other)
- Filter expenses by vehicle
- Dashboard with total costs and summary by category

## Tech Stack

- PHP 8.2
- MySQL
- HTML & CSS (no frameworks)
- PDO with prepared statements

## Setup

1. Import the database schema:

   ```bash
   mysql -u root < sql/schema.sql
   ```

2. Update database credentials in `config/database.php`

3. Run the built-in PHP server:

   ```bash
   php -S localhost:8000
   ```

4. Open `http://localhost:8000` in your browser
