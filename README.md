# Laravel REST API with JWT

This repository contains a Laravel REST API utilizing JWT authentication with a MySQL database.

## Features

- RESTful API design
- JWT authentication for secure access
- MySQL database

## Installation

1. `git clone [repo_url]`
2. `cd project_directory`
3. `composer install`
4. `cp .env.example .env` (Configure .env variables)
5. `php artisan key:generate`
6. `php artisan migrate`

## Configuration

Configure database and JWT secret in `.env` file.

## API Endpoints

JWT token required in `Authorization` header for protected routes (`Bearer <token>`).

## Database

MySQL database, migrations in `database/migrations`.

## Deployment

Standard Laravel deployment process.
