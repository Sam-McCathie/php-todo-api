# [WIP] PHP Todo API

A lightweight RESTful API for managing a todo list, built with PHP and MySQL, and containerized using Docker.

## Features

- Full CRUD functionality for managing todos.
- User-based todo management with relational database support.
- Dockerized environment for easy setup and deployment.

## Requirements

- Docker and Docker Compose installed on your system.

## Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/php-todo-api.git
   cd php-todo-api
   ```

2. Create a `.env` file for environment variables:

   ```bash
   touch .env
   ```

   Update the `.env` file the below credentials.

   ```bash
   MYSQL_ROOT_PASSWORD=rootpassword
   MYSQL_DATABASE=mydatabase
   MYSQL_USER=user
   MYSQL_PASSWORD=password
   ```

3. Start the Docker containers:

   ```bash
   docker-compose up -d
   ```

4. Access the API at `http://localhost:9000`.

## Database Initialization

The database schema is automatically initialized using the `init.sql` file. This file creates the necessary tables (`users` and `todos`) and sets up relationships.

## Endpoints

### Todos

- `GET /todos` - Retrieve all todos for a user (requires `userId` in the request body).
- `GET /todos/{id}` - Retrieve a specific todo by ID for a user (requires `userId` in the request body).
- `POST /todos` - Create a new todo (requires `userId` and `text` in the request body).
- `PATCH /todos/{id}` - Update an existing todo (requires `todoId` in the URL and at least one of `text` or `complete` in the request body).
- `DELETE /todos/{id}` - Delete a todo by ID.

### Users

- `GET /users` - Retrieve all users.
- `GET /users/{id}` - Retrieve a specific user by ID.
- `POST /users` - Create a new user (requires `username` in the request body).
- `PATCH /users/{id}` - Update an existing user (requires `userId` in the URL and `username` in the request body).
- `DELETE /users/{id}` - Delete a user by ID.

## Development

- The PHP server code is located in the `server` directory.
- The `.htaccess` file is used for routing requests to `index.php`.
