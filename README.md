# PHP Todo API

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

- `GET /todos` - Retrieve all todos.
- `POST /todos` - Create a new todo.
- `PUT /todos/{id}` - Update an existing todo.
- `DELETE /todos/{id}` - Delete a todo.
- `GET /users` - Retrieve all users.
- `POST /users` - Create a new user.
- `PUT /users/{id}` - Update an existing user.
- `DELETE /users/{id}` - Delete a user.

## Development

- The PHP server code is located in the `server` directory.
- The `.htaccess` file is used for routing requests to `index.php`.
